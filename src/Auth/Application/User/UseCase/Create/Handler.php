<?php

declare(strict_types=1);

namespace App\Auth\Application\User\UseCase\Create;

use App\Auth\Application\User\UseCase\Create\Result\Result;
use App\Auth\Domain\User\User;
use App\Auth\Domain\User\UserRepository;
use App\Common\Service\Core\Flusher;
use DateTimeImmutable;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class Handler
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly UserRepository $userRepository,
        private readonly Flusher $flusher,
    ) {
    }

    public function handle(Command $command): Result
    {
        if ($this->userRepository->hasByEmail($command->email)) {
            return Result::emailIsBusy();
        }

        $now = new DateTimeImmutable();
        $user = User::create(
            id: $this->userRepository->nextId(),
            createdAt: $now,
            updatedAt: $now,
            email: $command->email,
            roles: [User::ROLE_ADMIN],
            name: $command->name
        );
        $this->userRepository->add($user);
        $user->changePassword(
            $this->passwordHasher->hashPassword($user, $command->plainPassword)
        );

        $this->flusher->flush($user);

        return Result::success($user);
    }
}
