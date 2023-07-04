<?php

declare(strict_types=1);

namespace App\Auth\Application\Core\User\UseCase\Create\Result;

enum ResultCase
{
    case Success;
    case EmailIsBusy;

    public function isEqual(self $enum): bool
    {
        return $this->name === $enum->name;
    }
}
