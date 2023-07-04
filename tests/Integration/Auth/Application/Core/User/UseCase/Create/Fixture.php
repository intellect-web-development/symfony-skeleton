<?php

declare(strict_types=1);

namespace App\Tests\Integration\Auth\Application\Core\User\UseCase\Create;

use App\Auth\Domain\User\ValueObject\Id;
use App\Tests\Builder\Auth\User\UserBuilder;
use App\Tests\Tools\TestFixture;
use Doctrine\Persistence\ObjectManager;

class Fixture extends TestFixture
{
    public const ID = '10001';
    public const EMAIL = 'user@handler.create.email-is-busy';

    public function load(ObjectManager $manager): void
    {
        $user = (new UserBuilder())
            ->withId(new Id(self::ID))
            ->withEmail(self::EMAIL)
            ->build();
        $manager->persist($user);

        $manager->flush();
    }
}
