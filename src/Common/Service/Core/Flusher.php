<?php

declare(strict_types=1);

namespace App\Common\Service\Core;

use Doctrine\ORM\EntityManagerInterface;

readonly class Flusher
{
    public function __construct(
        private EntityManagerInterface $em,
        private EventDispatcher $dispatcher
    ) {
    }

    public function flush(AggregateRoot ...$roots): void
    {
        $this->em->flush();
        foreach ($roots as $root) {
            $this->dispatcher->dispatch($root->releaseEvents());
        }
    }
}
