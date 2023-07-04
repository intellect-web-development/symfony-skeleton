<?php

declare(strict_types=1);

namespace App\Auth\Core\User\Domain\Event;

readonly class UserCreatedEvent
{
    public function __construct(
        public string $id,
    ) {
    }
}
