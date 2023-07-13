<?php

declare(strict_types=1);

namespace App\Auth\Application\User\UseCase\ChangePassword;

use App\Auth\Application\User\UseCase\ChangePassword\Result\Result;
use App\Auth\Domain\User\UserRepository;
use App\Common\Service\Core\Flusher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class Handler
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly UserRepository $userRepository,
        private readonly Flusher $flusher
    ) {
    }

    public function handle(Command $command): Result
    {
        $user = $this->userRepository->findById($command->id);
        if (null === $user || !$this->passwordHasher->isPasswordValid($user, $command->oldPassword)) {
            return Result::invalidCredentials();
        }

        $user->changePassword(
            $this->passwordHasher->hashPassword($user, $command->newPassword)
        );

        $this->flusher->flush($user);

        return Result::success($user);
    }
}
