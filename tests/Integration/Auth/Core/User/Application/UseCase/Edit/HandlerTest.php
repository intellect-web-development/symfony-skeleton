<?php

namespace App\Tests\Integration\Auth\Core\User\Application\UseCase\Edit;

use App\Auth\Core\User\Application\UseCase\Edit\Command;
use App\Auth\Core\User\Application\UseCase\Edit\Handler;
use App\Auth\Core\User\Application\UseCase\Edit\Result\ResultCase;
use App\Auth\Core\User\Domain\UserRepository;
use App\Auth\Core\User\Domain\ValueObject\Id;
use App\Tests\Integration\IntegrationTestCase;

/** @covers \App\Auth\Core\User\Application\UseCase\Edit\Handler */
class HandlerTest extends IntegrationTestCase
{
    protected static Handler $handler;
    protected static UserRepository $userRepository;

    public function setUp(): void
    {
        parent::setUp();
        self::$handler = self::$containerTool->get(Handler::class);
        self::$userRepository = self::$containerTool->get(UserRepository::class);
    }

    protected static function withFixtures(): array
    {
        return [
            Fixture::class,
        ];
    }

    public function testHandleWithNullPayloadWhenSuccess(): void
    {
        $user = self::$userRepository->findById($wallId = new Id(Fixture::ID));
        self::assertNotNull($user);
        $expectedName = $user->getName();
        $expectedEmail = $user->getEmail();

        $result = self::$handler->handle(
            new Command(
                id: $wallId,
                name: null,
                email: null,
            )
        );
        self::assertTrue(
            $result->case->isEqual(ResultCase::Success)
        );
        self::assertNotNull($result->user);
        self::assertInstanceOf(Id::class, $result->user->getId());
        self::assertSame($expectedName, $result->user->getName());
        self::assertSame($expectedEmail, $result->user->getEmail());
    }

    public function testHandleWhenSuccess(): void
    {
        $result = self::$handler->handle(
            $command = new Command(
                id: new Id(Fixture::ID),
                name: self::$faker->name() . self::$faker->sha1(),
                email: self::$faker->email() . self::$faker->sha1()
            )
        );
        self::assertTrue(
            $result->case->isEqual(ResultCase::Success)
        );
        self::assertNotNull($result->user);
        self::assertInstanceOf(Id::class, $result->user->getId());
        self::assertSame($command->name, $result->user->getName());
        self::assertSame($command->email, $result->user->getEmail());
    }

    public function testHandleWhenUserNotExists(): void
    {
        $result = self::$handler->handle(
            new Command(
                id: new Id('100000'),
                name: self::$faker->name() . self::$faker->sha1(),
                email: self::$faker->email() . self::$faker->sha1()
            )
        );
        self::assertTrue(
            $result->case->isEqual(ResultCase::UserNotExists)
        );
        self::assertNull($result->user);
    }

    public function testHandleWhenSuccessWithSelfEmail(): void
    {
        $result = self::$handler->handle(
            $command = new Command(
                id: new Id(Fixture::ID),
                name: self::$faker->name() . self::$faker->sha1(),
                email: Fixture::SELF_EMAIL,
            )
        );
        self::assertTrue(
            $result->case->isEqual(ResultCase::Success)
        );
        self::assertNotNull($result->user);
        self::assertInstanceOf(Id::class, $result->user->getId());
        self::assertSame($command->name, $result->user->getName());
        self::assertSame($command->email, $result->user->getEmail());
    }

    public function testHandleWhenEmailIsBusy(): void
    {
        $result = self::$handler->handle(
            new Command(
                id: new Id(Fixture::ID),
                name: self::$faker->name() . self::$faker->sha1(),
                email: Fixture::BUSY_EMAIL,
            )
        );
        self::assertTrue(
            $result->case->isEqual(ResultCase::EmailIsBusy)
        );
        self::assertNull($result->user);
    }
}
