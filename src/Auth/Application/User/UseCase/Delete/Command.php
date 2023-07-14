<?php

declare(strict_types=1);

namespace App\Auth\Application\User\UseCase\Delete;

use App\Auth\Domain\User\ValueObject\Id;

final readonly class Command
{
    public function __construct(
        public Id $id,
    ) {
    }
}
