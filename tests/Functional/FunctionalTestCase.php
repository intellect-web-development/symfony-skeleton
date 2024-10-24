<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Auth\Infrastructure\Dictionary\User;
use App\Auth\Infrastructure\Security\JwtTokenizer;
use App\Auth\Infrastructure\Security\UserIdentity;
use App\Tests\Tools\AssertsTrait;
use App\Tests\Tools\TestFixture;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Faker\Generator;
use JsonException;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class FunctionalTestCase extends WebTestCase
{
    use AssertsTrait;

    protected static Generator $faker;

    private static KernelBrowser $clientBlank;
    protected static UserIdentity $userIdentity;
    protected static JwtTokenizer $jwtTokenizer;

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

        self::$jwtTokenizer = self::get(JwtTokenizer::class);

        $this->entityManager = self::get(EntityManagerInterface::class);
        $this->entityManager->getConnection()->beginTransaction();
        $this->entityManager->getConnection()->setAutoCommit(false);

        $this->client = clone self::$clientBlank;
        $this->client->disableReboot();

        $userEmail = (new DateTimeImmutable())->getTimestamp() . '-admin@dev.com';
        self::$userIdentity = new UserIdentity(
            id: '99999999',
            username: $userEmail,
            display: $userEmail,
            role: User::ROLE_ADMIN,
        );
        $this->client->loginUser(self::$userIdentity);

        foreach (static::withFixtures() as $fixtureClass) {
            /** @var TestFixture $fixture */
            $fixture = self::get($fixtureClass);
            $fixture->load($this->entityManager);
        }
    }

    /**
     * @param array<string> $headers
     * @param array<string> $jwtPayload
     *
     * @throws \Exception
     */
    protected function requestAuthJWT(
        string $method,
        string $url,
        string $body = '',
        array $headers = [],
        array $jwtPayload = []
    ): Response {
        return $this->request(
            $method,
            $url,
            $body,
            array_merge(
                $headers,
                [
                    'CONTENT_TYPE' => 'application/json',
                    'HTTP_AUTHORIZATION' => sprintf(
                        'Bearer %s',
                        self::$jwtTokenizer->generateAccessToken(self::$userIdentity, $jwtPayload)
                    ),
                ]
            )
        );
    }

    /**
     * @template T
     *
     * @param class-string<T> $id
     *
     * @return T
     * @throws \Exception
     */
    public static function get(string $id)
    {
        /** @var T $instance */
        $instance = parent::getContainer()->get($id);

        return $instance;
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
