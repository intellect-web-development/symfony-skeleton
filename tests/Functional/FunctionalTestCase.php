<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Auth\Core\User\Domain\User;
use App\Auth\Core\User\Domain\ValueObject\Id;
use App\Tests\Tools\AssertsTrait;
use App\Tests\Tools\Container;
use App\Tests\Tools\TestFixture;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Faker\Generator;
use JsonException;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

class FunctionalTestCase extends WebTestCase
{
    use AssertsTrait;

    protected static Container $containerTool;
    protected static Generator $faker;
    private static KernelBrowser $clientBlank;
    private static User $user;

    protected EntityManagerInterface $entityManager;
    protected KernelBrowser $client;

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

        /** @var PasswordHasherInterface $passwordHasher */
        $passwordHasher = self::$containerTool->get(PasswordHasherInterface::class);
        self::$user = User::create(
            id: new Id('99999999'),
            createdAt: new DateTimeImmutable(),
            updatedAt: new DateTimeImmutable(),
            email: ((new DateTimeImmutable())->getTimestamp()) . '-admin@dev.com',
            roles: [User::ROLE_ADMIN],
            name: 'admin@dev.com'
        );
        self::$user->changePassword($passwordHasher->hash('12345'));
        $this->entityManager->persist(self::$user);
        $this->entityManager->flush();
        $this->client->loginUser(self::$user);

        foreach (static::withFixtures() as $fixtureClass) {
            /** @var TestFixture $fixture */
            $fixture = self::$containerTool->get($fixtureClass);
            $fixture->load($this->entityManager);
        }
    }

    /**
     * @return class-string<TestFixture>[]
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
     * @throws JsonException
     */
    protected function parseEntityData(?string $content = null): array
    {
        if (null === $content) {
            return [];
        }

        return json_decode($content, true, 512, JSON_THROW_ON_ERROR)['data'];
    }

    /**
     * @throws JsonException
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
