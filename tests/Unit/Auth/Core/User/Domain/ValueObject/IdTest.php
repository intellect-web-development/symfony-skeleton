<?php

declare(strict_types=1);

namespace App\Tests\Unit\Auth\Core\User\Domain\ValueObject;

use App\Auth\Core\User\Domain\ValueObject\Id;
use App\Tests\Unit\UnitTestCase;

/** @covers \App\Auth\Core\User\Domain\ValueObject\Id */
class IdTest extends UnitTestCase
{
    public function testToString(): void
    {
        $value = (string) random_int(1, 999);
        self::assertEquals($value, (new Id($value))->__toString());
    }

    public function testGetValue(): void
    {
        $value = (string) random_int(1, 999);
        self::assertEquals($value, (new Id($value))->getValue());
    }
}
