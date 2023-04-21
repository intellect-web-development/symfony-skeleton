<?php

declare(strict_types=1);

namespace App\Auth\DataFixtures;

use App\Tests\Builder\Auth\User\UserBuilder;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RandomFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $userBuilder = new UserBuilder();
        foreach (range(30, 100) as $i) {
            $user = $userBuilder->build();
            $manager->persist($user);
        }

        $manager->flush();
    }
}
