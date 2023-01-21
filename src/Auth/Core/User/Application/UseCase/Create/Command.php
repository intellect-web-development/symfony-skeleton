<?php

namespace App\Auth\Core\User\Application\UseCase\Create;

class Command
{
    public function __construct(
        public readonly string $plainPassword,
        public readonly string $name,
        public readonly string $email,
    ) {
    }
}
