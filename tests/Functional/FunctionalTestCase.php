<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Tests\Tools\AssertsTrait;
use App\Tests\Tools\Container;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Faker\Generator;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class FunctionalTestCase extends WebTestCase
{
    use AssertsTrait;

    protected static Container $containerTool;
    protected EntityManagerInterface $entityManager;
    protected KernelBrowser $client;
    protected static Generator $faker;

    private static KernelBrowser $clientBlank;

    public static function setUpBeforeClass(): void
    {
        self::$clientBlank = static::createClient();
        parent::setUpBeforeClass();

        self::$faker = Factory::create();
    }

    protected function setUp(): void
    {
        parent::setUp();
        self::$containerTool = new Container(parent::getContainer());

        $this->entityManager = self::$containerTool->get(EntityManagerInterface::class);
        $this->entityManager->getConnection()->beginTransaction();
        $this->entityManager->getConnection()->setAutoCommit(false);

        $this->client = clone self::$clientBlank;
        $this->client->disableReboot();

        foreach (static::withFixtures() as $fixtureClass) {
            /** @var FixtureInterface $fixture */
            $fixture = self::$containerTool->get($fixtureClass);
            $fixture->load($this->entityManager);
        }
    }

    /**
     * @return class-string<FixtureInterface>[]
     */
    protected static function withFixtures(): array
    {
        return [];
    }

    protected function tearDown(): void
    {
        $this->entityManager->getConnection()->rollback();
        $this->entityManager->close();
        parent::tearDown();
    }

    /**
     * @throws \JsonException
     */
    protected function parseEntityData(?string $content = null): array
    {
        if (null === $content) {
            return [];
        }

        return json_decode($content, true, 512, JSON_THROW_ON_ERROR)['data'];
    }

    /**
     * @throws \JsonException
     */
    protected function parseEntitiesData(?string $content = null): array
    {
        if (null === $content) {
            return [];
        }

        return json_decode($content, true, 512, JSON_THROW_ON_ERROR)['data']['data'];
    }

    /**
     * @param array<string> $headers
     */
    protected function request(string $method, string $url, string $body = '', array $headers = []): Response
    {
        $this->client->request($method, $url, [], [], $headers, $body);

        return $this->client->getResponse();
    }
}
