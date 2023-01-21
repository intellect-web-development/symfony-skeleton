<?php

declare(strict_types=1);

namespace App\Tests\Integration\Auth\Core\User\Application\UseCase\Delete;

use App\Auth\Core\User\Domain\ValueObject\Id;
use App\Tests\Builder\Auth\User\UserBuilder;
use App\Tests\Tools\TestFixture;
use Doctrine\Persistence\ObjectManager;

class Fixture extends TestFixture
{
    public const ID = '10002';

    public function load(ObjectManager $manager): void
    {
        $user = (new UserBuilder())
            ->withId(new Id(self::ID))
            ->build();
        $manager->persist($user);

        $manager->flush();
    }
}
