<?php

declare(strict_types=1);

namespace App\Auth\Infrastructure\Security;

use App\Auth\Domain\User\User;
use Generator;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Security\Core\User\UserInterface;

readonly class UserIdentityResolver implements ValueResolverInterface
{
    public function __construct(
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
            return [];
        }

        yield $userIdentity;
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        $type = $argument->getType();

        return null !== $type && is_subclass_of($type, UserInterface::class);
    }
}
