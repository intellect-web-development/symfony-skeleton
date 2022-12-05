<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use App\Tests\Tools\AssertsTrait;
use App\Tests\Tools\Container;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Faker\Generator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class IntegrationTestCase extends KernelTestCase
{
    use AssertsTrait;

    protected EntityManagerInterface $entityManager;
    protected static Generator $faker;

    /** @var Container */
    protected static Container $containerTool;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        static::bootKernel();

        self::$faker = Factory::create();
        self::$containerTool = new Container(parent::getContainer());
    }

    protected function setUp(): void
    {
        parent::setUp();
        self::$containerTool = new Container(parent::getContainer());

        $this->entityManager = self::$containerTool->get(EntityManagerInterface::class);
        $this->entityManager->getConnection()->beginTransaction();
        $this->entityManager->getConnection()->setAutoCommit(false);
    }

    protected function tearDown(): void
    {
        $this->entityManager->getConnection()->rollback();
        parent::tearDown();
    }

    /**
     * @param mixed $value
     *
     * @throws \ReflectionException
     */
    protected static function bindMock(object $object, string $property, $value): void
    {
        $className = get_class($object);
        try {
            $refProperty = self::getReflectionProperty($className, $property);
            $refProperty->setValue($object, $value);
        } catch (\ReflectionException $reflectionException) {
            if ($object instanceof \ArrayAccess) {
                $object[$property] = $value;
            } else {
                throw $reflectionException;
            }
        }
    }

    /**
     * @param class-string<mixed> $className
     *
     * @throws \ReflectionException
     */
    private static function getReflectionProperty(string $className, string $property): \ReflectionProperty
    {
        $refProperty = new \ReflectionProperty($className, $property);
        $refProperty->setAccessible(true);

        return $refProperty;
    }
}
