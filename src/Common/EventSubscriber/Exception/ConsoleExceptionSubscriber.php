<?php

declare(strict_types=1);

namespace App\Common\EventSubscriber\Exception;

use App\Common\Exception\Domain\DomainException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleErrorEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Throwable;

#[AsEventListener(event: ConsoleEvents::ERROR, method: 'logException', priority: 1)]
class ConsoleExceptionSubscriber
{
    public function __construct(
        private readonly LoggerInterface $logger,
    ) {
    }

    public function logException(ConsoleErrorEvent $event): void
    {
        $exception = $event->getError();
        try {
            throw $exception;
        } catch (DomainException $exception) {
            $this->logger->warning($exception->getMessage());
        } catch (Throwable $exception) {
            $this->logger->error($exception->getMessage());
        }
    }
}
