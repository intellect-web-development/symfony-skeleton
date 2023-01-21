<?php

namespace App\Tests\Integration\Auth\Core\User\Application\UseCase\Delete;

use App\Auth\Core\User\Application\UseCase\Delete\Command;
use App\Auth\Core\User\Application\UseCase\Delete\Handler;
use App\Auth\Core\User\Application\UseCase\Delete\Result\ResultCase;
use App\Auth\Core\User\Domain\UserRepository;
use App\Auth\Core\User\Domain\ValueObject\Id;
use App\Tests\Integration\IntegrationTestCase;

/** @covers \App\Auth\Core\User\Application\UseCase\Delete\Handler */
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

    public function testHandleWhenSuccess(): void
    {
        $result = self::$handler->handle(
            $command = new Command(
                id: new Id(Fixture::ID),
            )
        );
        self::assertTrue(
            $result->case->isEqual(ResultCase::Success)
        );
        self::assertNull(
            self::$userRepository->findById($command->id)
        );
    }

    public function testHandleWhenUserNotExists(): void
    {
        $result = self::$handler->handle(
            new Command(
                id: new Id('100000'),
            )
        );
        self::assertTrue(
            $result->case->isEqual(ResultCase::UserNotExists)
        );
        self::assertNull($result->user);
    }
}
