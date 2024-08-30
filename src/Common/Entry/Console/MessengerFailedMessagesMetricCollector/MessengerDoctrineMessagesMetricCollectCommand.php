<?php

declare(strict_types=1);

namespace App\Common\Entry\Console\MessengerFailedMessagesMetricCollector;

use App\Common\Service\Metrics\AdapterInterface;
use Doctrine\DBAL\Connection;
use IWD\SymfonyEntryContract\Service\CliContractResolver;
use IWD\SymfonyEntryContract\Attribute\CliContract;
use IWD\SymfonyEntryContract\Console\CliCommand;
use IWD\SymfonyEntryContract\Interfaces\InputContractInterface;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'app:common:messenger-doctrine-messages-collect',
    description: 'Collect doctrine messages in queue',
)]
#[CliContract(class: InputContract::class)]
class MessengerDoctrineMessagesMetricCollectCommand extends CliCommand
{
    public function __construct(
        CliContractResolver $cliContractResolver,
        private readonly Connection $connection,
        private readonly AdapterInterface $adapter,
    ) {
        parent::__construct($cliContractResolver);
    }

    /**
     * @param InputContract $inputContract
     */
    protected function handle(InputContractInterface $inputContract): int
    {
        $queuesAndCounts = $this->connection->prepare(
            <<<SQL
                WITH queue_names AS (
                    SELECT DISTINCT queue_name
                    FROM {$inputContract->messengerTable}
                ),
                     queue_counts AS (SELECT queue_name, COUNT(*) AS count FROM {$inputContract->messengerTable} GROUP BY queue_name)
                SELECT
                    queue_names.queue_name,
                    COALESCE(queue_counts.count, 0) AS message_count
                FROM
                    queue_names LEFT JOIN queue_counts ON queue_names.queue_name = queue_counts.queue_name
                SQL
        )->executeQuery()->fetchAllAssociative();

        foreach ($queuesAndCounts as $queuesAndCount) {
            $gauge = $this->adapter->createGauge(
                name: 'actual_messages_in_doctrine_queue',
                help: 'Actual messages in doctrine queue',
                labels: [
                    'queue',
                ]
            );
            $gauge->set($queuesAndCount['message_count'], [
                $queuesAndCount['queue_name'],
            ]);

            $this->io->success($queuesAndCount['queue_name'] . ': ' . $queuesAndCount['message_count']);
        }

        return self::SUCCESS;
    }
}
