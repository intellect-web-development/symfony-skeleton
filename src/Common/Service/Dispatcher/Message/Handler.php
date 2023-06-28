<?php

declare(strict_types=1);

namespace App\Common\Service\Dispatcher\Message;

use App\Common\Service\Dispatcher\NamedEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class Handler
{
    private EventDispatcherInterface $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function __invoke(Message $message): void
    {
        $event = $message->getEvent();
        $eventName = $event instanceof NamedEvent ? $event::getEventName() : get_class($event);
        $this->dispatcher->dispatch(
            $event,
            $eventName
        );
    }
}
