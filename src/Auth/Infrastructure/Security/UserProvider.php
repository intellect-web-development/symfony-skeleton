<?php

declare(strict_types=1);

namespace App\Auth\Infrastructure\Security;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

readonly class UserProvider implements UserProviderInterface
{
    public function __construct(
        private UserIdentityFetcher $userIdentityFetcher,
        private RequestStack $requestStack,
    ) {
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof UserIdentity) {
            throw new UnsupportedUserException('Invalid user class ' . $user::class);
        }

        return $user;
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $request = $this->requestStack->getMainRequest();
        if (null === $request) {
            throw new UserNotFoundException();
        }

        $userIdentity = $this->userIdentityFetcher->tryFetch($request);
        if (null === $userIdentity) {
            throw new UserNotFoundException();
        }

        return $userIdentity;
    }

    /**
     * @psalm-suppress MoreSpecificImplementedParamType
     *
     * @param class-string<UserInterface> $class
     */
    public function supportsClass(string $class): bool
    {
        return UserIdentity::class === $class;
    }
}
