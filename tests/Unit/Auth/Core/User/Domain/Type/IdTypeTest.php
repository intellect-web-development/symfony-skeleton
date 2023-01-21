<?php

declare(strict_types=1);

namespace App\Tests\Unit\Auth\Core\User\Domain\Type;

use App\Auth\Core\User\Domain\Type\IdType;
use App\Auth\Core\User\Domain\ValueObject\Id;
use App\Tests\Unit\UnitTestCase;
use Doctrine\DBAL\Platforms\AbstractPlatform;

/** @covers \App\Auth\Core\User\Domain\Type\IdType */
class IdTypeTest extends UnitTestCase
{
    public function testConvertToPHPValueSuccess(): void
    {
        /** @psalm-suppress InternalMethod */
        $idType = new IdType();
        $abstractPlatformMock = $this->createMock(AbstractPlatform::class);
        $value = '835';

        $phpValue = $idType->convertToPHPValue($value, $abstractPlatformMock);

        self::assertInstanceOf(Id::class, $phpValue);
        self::assertEquals($value, $phpValue->getValue());
    }

    public function testConvertToPHPValueWithEmptyValue(): void
    {
        /** @psalm-suppress InternalMethod */
        $idType = new IdType();
        $abstractPlatformMock = $this->createMock(AbstractPlatform::class);

        self::assertNull(
            $idType->convertToPHPValue(null, $abstractPlatformMock)
        );
    }

    public function testConvertToDatabaseValueSuccess(): void
    {
        /** @psalm-suppress InternalMethod */
        $idType = new IdType();
        $abstractPlatformMock = $this->createMock(AbstractPlatform::class);
        $value = new Id('146');

        $convertedDatabaseValue = $idType->convertToDatabaseValue($value, $abstractPlatformMock);
        self::assertEquals($value->getValue(), $convertedDatabaseValue);
    }

    public function testConvertToDatabaseValueWithEmptyValue(): void
    {
        /** @psalm-suppress InternalMethod */
        $idType = new IdType();
        $abstractPlatformMock = $this->createMock(AbstractPlatform::class);

        self::assertNull(
            $idType->convertToDatabaseValue(null, $abstractPlatformMock)
        );
    }

    public function testGetName(): void
    {
        /** @psalm-suppress InternalMethod */
        $idType = new IdType();
        self::assertEquals(IdType::NAME, $idType->getName());
    }

    public function testRequiresSQLCommentHint(): void
    {
        /** @psalm-suppress InternalMethod */
        $idType = new IdType();
        $abstractPlatformMock = $this->createMock(AbstractPlatform::class);
        self::assertTrue(
            $idType->requiresSQLCommentHint($abstractPlatformMock)
        );
    }
}
