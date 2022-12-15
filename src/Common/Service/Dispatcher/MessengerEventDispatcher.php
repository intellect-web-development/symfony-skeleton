<?php

declare(strict_types=1);

namespace App\Common\Service\Dispatcher;

use App\Common\Service\Core\EventDispatcher;
use App\Common\Service\Dispatcher\Message\Message;
use Symfony\Component\Messenger\MessageBusInterface;

class MessengerEventDispatcher implements EventDispatcher
{
    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
    }

    public function dispatch(array $events): void
    {
        foreach ($events as $event) {
            $this->bus->dispatch(new Message($event));
        }
    }
}
