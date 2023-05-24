<?php

declare(strict_types=1);

namespace App\Auth\Security;

use Generator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

#todo: ValueResolverInterface переделать
class UserIdentityResolver implements ArgumentValueResolverInterface
{
    public function __construct(
        private readonly UserProviderInterface $userProvider,
        private readonly JwtTokenizer $jwtTokenizer,
    ) {
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        $type = $argument->getType();

        return null !== $type && is_subclass_of($type, UserInterface::class);
    }

    public function resolve(Request $request, ArgumentMetadata $argument): Generator
    {
        $authorization = $request->headers->get('Authorization');
        if (null === $authorization) {
            throw new AccessDeniedException('Authorization token not found');
        }
        if (!(str_contains($authorization, 'Bearer ') && strlen($authorization) > 7)) {
            throw new AccessDeniedException('Invalid authorization token');
        }
        [$type, $token] = explode(' ', $authorization);
        /** @var UserIdentity $user */
        $user = $this->userProvider->loadUserByIdentifier($this->jwtTokenizer->decode($token)['username']);

        yield $user;
    }
}
