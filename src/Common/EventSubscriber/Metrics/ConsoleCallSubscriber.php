<?php

declare(strict_types=1);

namespace App\Common\EventSubscriber\Metrics;

use App\Common\Service\Metrics\AdapterInterface;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: ConsoleEvents::COMMAND, method: 'onSendMetrics', priority: 1)]
class ConsoleCallSubscriber
{
    public function __construct(
        private readonly AdapterInterface $adapter
    ) {
    }

    public function onSendMetrics(ConsoleCommandEvent $event): void
    {
        $counter = $this->adapter->createCounter(
            name: 'console_call',
            help: 'Console calls counter',
            labels: [
                'name',
            ]
        );

        $command = $event->getCommand();
        if (null !== $command) {
            $counter->inc([
                (string) $command->getName(),
            ]);
        }
    }
}
