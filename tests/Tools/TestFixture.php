<?php

namespace App\Tests\Tools;

use Doctrine\Persistence\ObjectManager;

abstract class TestFixture
{
    abstract public function load(ObjectManager $manager): void;
}
