<?php

namespace App\Tests\Integration\Auth\Core\User\Application\UseCase\ChangePassword;

use App\Auth\Core\User\Application\UseCase\ChangePassword\Command;
use App\Auth\Core\User\Application\UseCase\ChangePassword\Handler;
use App\Auth\Core\User\Application\UseCase\ChangePassword\Result\ResultCase;
use App\Auth\Core\User\Domain\ValueObject\Id;
use App\Tests\Integration\IntegrationTestCase;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

/** @covers \App\Auth\Core\User\Application\UseCase\ChangePassword\Handler */
class HandlerTest extends IntegrationTestCase
{
    protected static Handler $handler;
    protected static PasswordHasherInterface $passwordHasher;

    public function setUp(): void
    {
        parent::setUp();
        self::$handler = self::$containerTool->get(Handler::class);
        self::$passwordHasher = self::$containerTool->get(PasswordHasherInterface::class);
    }

    protected static function withFixtures(): array
    {
        return [
            Fixture::class,
        ];
    }

    public function testHandleWhenSuccess(): void
    {
        $result = self::$handler->handle(
            $command = new Command(
                id: new Id(Fixture::ID),
                newPassword: self::$faker->password,
                oldPassword: Fixture::PASSWORD
            )
        );
        self::assertTrue(
            $result->case->isEqual(ResultCase::Success)
        );
        self::assertNotNull($result->user);
        self::assertTrue(
            self::$passwordHasher->verify($result->user->getPassword(), $command->newPassword)
        );
        self::assertFalse(
            self::$passwordHasher->verify($result->user->getPassword(), $command->oldPassword)
        );
    }

    public function testHandleWhenInvalidCredentials(): void
    {
        $result = self::$handler->handle(
            new Command(
                id: new Id(Fixture::ID),
                newPassword: self::$faker->password,
                oldPassword: self::$faker->password . self::$faker->sha1
            )
        );
        self::assertTrue(
            $result->case->isEqual(ResultCase::InvalidCredentials)
        );
        self::assertNull($result->user);
    }
}
