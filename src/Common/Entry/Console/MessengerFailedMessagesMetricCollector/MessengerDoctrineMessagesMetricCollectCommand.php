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
        $hasTableStmt = $this->connection->prepare(
            <<<SQL
                SELECT EXISTS (
                SELECT 1
                FROM information_schema.tables
                WHERE table_name = '{$inputContract->messengerTable}'
            );
            SQL
        );

        $hasTable = (bool) $hasTableStmt->executeQuery()->fetchOne();
        if ($hasTable) {
            $stmt = $this->connection->prepare(
                <<<SQL
                select count(1) from {$inputContract->messengerTable}
                where queue_name = :queueName;
                SQL
            );

            $stmt->bindValue('queueName', $inputContract->queueName);
            $count = $stmt->executeQuery()->fetchOne();
        } else {
            $count = 0;
        }

        $gauge = $this->adapter->createGauge(
            name: 'actual_messages_in_doctrine_queue',
            help: 'Actual messages in doctrine queue',
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
