<?php

declare(strict_types=1);

namespace App\Auth\Infrastructure\Security;

use App\Auth\Domain\User\User;
use Generator;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

readonly class UserIdentityResolver implements ValueResolverInterface
{
    public function __construct(
        private UserProviderInterface $userProvider,
        private JwtTokenizer $jwtTokenizer,
        private Security $security,
    ) {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): Generator
    {
        if (!$this->supports($request, $argument)) {
            return [];
        }
        if (($user = $this->security->getUser()) !== null) {
            /** @var User $user */
            $userIdentity = UserProvider::identityByUser($user);
        }
        if (!isset($userIdentity)) {
            $userIdentity = $this->getFromBearerToken($request);
        }

        yield $userIdentity;
    }

    private function getFromBearerToken(Request $request): UserIdentity
    {
        $authorization = $request->headers->get('Authorization');
        if (null === $authorization) {
            throw new AccessDeniedException('Authorization token not found');
        }
        if (!(str_contains($authorization, 'Bearer ') && strlen($authorization) > 7)) {
            throw new AccessDeniedException('Invalid authorization token');
        }
        [$type, $token] = explode(' ', $authorization);
        /** @var UserIdentity $userIdentity */
        $userIdentity = $this->userProvider->loadUserByIdentifier($this->jwtTokenizer->decode($token)['username']);

        return $userIdentity;
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        $type = $argument->getType();

        return null !== $type && is_subclass_of($type, UserInterface::class);
    }
}
