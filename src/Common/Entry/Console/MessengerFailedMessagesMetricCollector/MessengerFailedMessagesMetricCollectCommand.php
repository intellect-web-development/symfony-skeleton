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
    name: 'app:common:messenger-failed-messages-collect',
    description: 'Collect failed messages in metric',
)]
#[CliContract(class: InputContract::class)]
class MessengerFailedMessagesMetricCollectCommand extends CliCommand
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
        $stmt = $this->connection->prepare(
            <<<SQL
                select count(1) from messenger_messages
                where queue_name = :queueName;
                SQL
        );

        $stmt->bindValue('queueName', $inputContract->queueName);
        $count = $stmt->executeQuery()->fetchOne();

        $gauge = $this->adapter->createGauge(
            name: 'actual_failed_messages',
            help: 'Actual failed messages in queue',
            labels: [
                'queue',
            ]
        );
        $gauge->set($count, [
            $inputContract->queueName,
        ]);


        $this->io->success((string) $count);

        return self::SUCCESS;
    }
}
