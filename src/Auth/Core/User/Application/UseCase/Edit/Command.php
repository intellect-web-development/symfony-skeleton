<?php

namespace App\Auth\Core\User\Application\UseCase\Edit;

use App\Auth\Core\User\Domain\ValueObject\Id;

class Command
{
    public function __construct(
        public readonly Id $id,
        public readonly ?string $name,
        public readonly ?string $email,
    ) {
    }
}
