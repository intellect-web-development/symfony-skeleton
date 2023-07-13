<?php

declare(strict_types=1);

namespace App\Auth\Application\User\UseCase\Create;

class Command
{
    public function __construct(
        public readonly string $plainPassword,
        public readonly string $name,
        public readonly string $email,
    ) {
    }
}
