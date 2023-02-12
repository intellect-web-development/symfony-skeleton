<?php

declare(strict_types=1);

namespace App\Tests\Integration\Auth\Core\User\Domain;

use App\Auth\Core\User\Domain\ValueObject\Id;
use App\Tests\Builder\Auth\User\UserBuilder;
use App\Tests\Tools\TestFixture;
use Doctrine\Persistence\ObjectManager;

class CommonFixture extends TestFixture
{
    public const ID = '10000';
    public const EMAIL = 'user@repository-common.email';

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