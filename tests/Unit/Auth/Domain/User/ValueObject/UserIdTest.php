<?php

declare(strict_types=1);

namespace App\Tests\Unit\Auth\Domain\User\ValueObject;

use App\Auth\Domain\User\ValueObject\UserId;
use App\Tests\Unit\UnitTestCase;

/** @covers \App\Auth\Domain\User\ValueObject\UserId */
class UserIdTest extends UnitTestCase
{
    public function testToString(): void
    {
        $value = (string) random_int(1, 999);
        self::assertSame($value, (new UserId($value))->__toString());
    }

    public function testGetValue(): void
    {
        $value = (string) random_int(1, 999);
        self::assertSame($value, (new UserId($value))->getValue());
    }
}
