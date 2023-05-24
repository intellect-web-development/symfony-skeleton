<?php

declare(strict_types=1);

namespace App\Auth\Security;

use App\Common\Exception\SystemException;
use DateInterval;
use DateTime;
use Exception;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\Key;
use OpenSSLAsymmetricKey;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use UnexpectedValueException;

class JwtTokenizer
{
    private JWT $jwt;

    public function __construct(JWT $jwt)
    {
        $this->jwt = $jwt;
    }

    /**
     * @throws Exception
     */
    public function generateAccessToken(UserIdentity $user, array $payload = []): string
    {
        $privateKey = $this->getPrivateKey();

        $payload = array_merge(
            [
                'iat' => (new DateTime())->getTimestamp(),
                'exp' => (new DateTime())->add(new DateInterval('PT60M'))->getTimestamp(),
                'id' => $user->getId(),
                'username' => $user->getUserIdentifier(),
                'roles' => $user->getRoles(),
            ],
            $payload
        );

        /** @var resource $privateKey */
        return \Firebase\JWT\JWT::encode($payload, $privateKey, 'RS256');
    }

    /**
     * @throws Exception
     */
    public function generateRefreshToken(UserIdentity $user): string
    {
        $privateKey = $this->getPrivateKey();

        $payload = [
            'iat' => (new DateTime())->getTimestamp(),
            'exp' => (new DateTime())->add(new DateInterval('P30D'))->getTimestamp(),
            'refresh.userId' => $user->getId(),
        ];

        /** @var resource $privateKey */
        return \Firebase\JWT\JWT::encode($payload, $privateKey, 'RS256');
    }

    public function getUserIdByRefreshToken(string $refreshToken): string
    {
        try {
            $tokenDecode = $this->decode($refreshToken);
        } catch (UnexpectedValueException $exception) {
            throw new AccessDeniedException($exception->getMessage(), $exception);
        }

        if (!isset($tokenDecode['refresh.userId'])) {
            throw new AccessDeniedException('This token is not refresh');
        }

        return $tokenDecode['refresh.userId'];
    }

    public function decode(string $token): array
    {
        $publicKey = file_get_contents($this->jwt->publicKey);
        if (false === $publicKey) {
            throw new SystemException('Public key not found');
        }

        return (array) \Firebase\JWT\JWT::decode($token, new Key($publicKey, 'RS256'));
    }

    public function tokenIsExpired(string $token): bool
    {
        try {
            return $this->decode($token)['exp'] < (new DateTime())->getTimestamp();
        } catch (ExpiredException $exception) {
            return true;
        }
    }

    /**
     * @throws Exception
     */
    protected function getPrivateKey(): OpenSSLAsymmetricKey
    {
        $key = openssl_pkey_get_private(
            'file://' . $this->jwt->privateKey,
            $this->jwt->passPhrase
        );

        if (false === $key) {
            throw new Exception('Invalid private key', 500);
        }

        return $key;
    }
}
