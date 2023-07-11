<?php

declare(strict_types=1);

namespace App\Auth\Application\User\UseCase\Edit;

use App\Auth\Domain\User\ValueObject\Id;

class Command
{
    public function __construct(
        public readonly Id $id,
        public readonly ?string $name,
        public readonly ?string $email,
    ) {
    }
}
