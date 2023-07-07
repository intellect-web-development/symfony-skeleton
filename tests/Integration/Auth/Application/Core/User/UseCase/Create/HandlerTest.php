<?php

declare(strict_types=1);

namespace App\Tests\Integration\Auth\Application\Core\User\UseCase\Create;

use App\Auth\Application\Core\User\UseCase\Create\Command;
use App\Auth\Application\Core\User\UseCase\Create\Handler;
use App\Auth\Application\Core\User\UseCase\Create\Result\ResultCase;
use App\Auth\Domain\User\User;
use App\Auth\Domain\User\ValueObject\Id;
use App\Tests\Integration\IntegrationTestCase;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

/** @covers \App\Auth\Application\Core\User\UseCase\Create\Handler */
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
                plainPassword: self::$faker->password(),
                name: self::$faker->name() . md5(random_bytes(255)),
                email: self::$faker->email() . md5(random_bytes(255))
            )
        );
        self::assertTrue(
            $result->case->isEqual(ResultCase::Success)
        );
        self::assertNotNull($result->user);
        self::assertInstanceOf(Id::class, $result->user->getId());
        self::assertSame($command->name, $result->user->getName());
        self::assertSame($command->email, $result->user->getEmail());
        self::assertSame([User::ROLE_ADMIN], $result->user->getRoles());
        self::assertDatetimeNow($result->user->getCreatedAt());
        self::assertDatetimeNow($result->user->getUpdatedAt());
        self::assertTrue(
            self::$passwordHasher->verify(
                $result->user->getPassword(),
                $command->plainPassword
            )
        );
    }

    public function testHandleWhenEmailIsBusy(): void
    {
        $result = self::$handler->handle(
            new Command(
                plainPassword: self::$faker->password(),
                name: self::$faker->name() . md5(random_bytes(255)),
                email: Fixture::EMAIL
            )
        );
        self::assertTrue(
            $result->case->isEqual(ResultCase::EmailIsBusy)
        );
        self::assertNull($result->user);
    }
}