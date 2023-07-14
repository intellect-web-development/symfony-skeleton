<?php

declare(strict_types=1);

namespace App\Auth\Application\User\UseCase\ChangePassword;

use App\Auth\Domain\User\ValueObject\Id;

final readonly class Command
{
    public function __construct(
        public Id $id,
        public string $newPassword,
        public string $oldPassword,
    ) {
    }
}
