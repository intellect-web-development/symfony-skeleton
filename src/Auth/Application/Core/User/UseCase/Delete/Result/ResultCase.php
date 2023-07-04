<?php

declare(strict_types=1);

namespace App\Auth\Application\Core\User\UseCase\Delete\Result;

enum ResultCase
{
    case Success;
    case UserNotExists;

    public function isEqual(self $enum): bool
    {
        return $this->name === $enum->name;
    }
}
