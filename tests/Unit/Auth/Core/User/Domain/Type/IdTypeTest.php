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
        $value = '708';

        /** @var Id|null $phpValue */
        $phpValue = $idType->convertToPHPValue($value, $abstractPlatformMock);

        self::assertSame($value, $phpValue?->getValue());
        self::assertInstanceOf(Id::class, $phpValue);
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
        $value = new Id('697');

        $convertedDatabaseValue = $idType->convertToDatabaseValue($value, $abstractPlatformMock);
        self::assertSame($value->getValue(), $convertedDatabaseValue);
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
        self::assertSame(IdType::NAME, $idType->getName());
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
