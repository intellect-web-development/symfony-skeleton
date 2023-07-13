<?php

declare(strict_types=1);

namespace App\Auth\Application\User\UseCase\ChangePassword;

use App\Auth\Domain\User\ValueObject\Id;

class Command
{
    public function __construct(
        public readonly Id $id,
        public readonly string $newPassword,
        public readonly string $oldPassword,
    ) {
    }
}
