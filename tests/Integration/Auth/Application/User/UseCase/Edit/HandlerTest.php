<?php

declare(strict_types=1);

namespace App\Tests\Integration\Auth\Application\User\UseCase\Edit;

use App\Auth\Application\User\UseCase\Edit\Command;
use App\Auth\Application\User\UseCase\Edit\Handler;
use App\Auth\Domain\User\Exception\UserEmailAlreadyTakenException;
use App\Auth\Domain\User\Exception\UserNotFoundException;
use App\Auth\Domain\User\User;
use App\Auth\Domain\User\UserRepository;
use App\Auth\Domain\User\ValueObject\UserId;
use App\Tests\Integration\IntegrationTestCase;

/** @covers \App\Auth\Application\User\UseCase\Edit\Handler */
class HandlerTest extends IntegrationTestCase
{
    protected static Handler $handler;
    protected static UserRepository $userRepository;

    public function setUp(): void
    {
        parent::setUp();
        self::$handler = self::get(Handler::class);
        self::$userRepository = self::get(UserRepository::class);
    }

    protected static function withFixtures(): array
    {
        return [
            Fixture::class,
        ];
    }

    public function testHandleWithNullPayloadWhenSuccess(): void
    {
        $user = self::$userRepository->findById($wallId = new UserId(Fixture::ID));
        self::assertNotNull($user);
        $expectedEmail = $user->getEmail();
        $expectedRole = $user->getRole();

        $result = self::$handler->handle(
            new Command(
                id: $wallId,
                email: null,
                role: null,
            )
        );
        self::assertNotNull($result->user);
        self::assertInstanceOf(UserId::class, $result->user->getId());
        self::assertSame($expectedEmail, $result->user->getEmail());
        self::assertSame($expectedRole, $result->user->getRole());
    }

    public function testHandleWhenSuccess(): void
    {
        $result = self::$handler->handle(
            $command = new Command(
                id: new UserId(Fixture::ID),
                email: self::$faker->email() . self::$faker->sha1(),
                role: User::ROLE_ADMIN,
            )
        );
        self::assertNotNull($result->user);
        self::assertInstanceOf(UserId::class, $result->user->getId());
        self::assertSame($command->email, $result->user->getEmail());
        self::assertSame($command->role, $result->user->getRole());
    }

    public function testHandleWhenUserNotExists(): void
    {
        self::expectException(UserNotFoundException::class);
        self::$handler->handle(
            new Command(
                id: new UserId('100000'),
                email: self::$faker->email() . self::$faker->sha1(),
                role: User::ROLE_ADMIN,
            )
        );
    }

    public function testHandleWhenSuccessWithSelfEmail(): void
    {
        $result = self::$handler->handle(
            $command = new Command(
                id: new UserId(Fixture::ID),
                email: Fixture::SELF_EMAIL,
                role: User::ROLE_ADMIN,
            )
        );
        self::assertNotNull($result->user);
        self::assertInstanceOf(UserId::class, $result->user->getId());
        self::assertSame($command->email, $result->user->getEmail());
    }

    public function testHandleWhenEmailIsBusy(): void
    {
        self::expectException(UserEmailAlreadyTakenException::class);
        self::$handler->handle(
            new Command(
                id: new UserId(Fixture::ID),
                email: Fixture::BUSY_EMAIL,
                role: User::ROLE_ADMIN,
            )
        );
    }
}
