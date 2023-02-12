<?php

declare(strict_types=1);

namespace App\Auth\Core\User\Domain\ValueObject;

use Webmozart\Assert\Assert;

class Id
{
    public function __construct(
        private readonly string $value
    ) {
        Assert::notEmpty($value);
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}