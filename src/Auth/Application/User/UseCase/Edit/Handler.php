<?php

declare(strict_types=1);

namespace App\Auth\Application\User\UseCase\Edit;

use App\Auth\Application\User\UseCase\Edit\Result\Result;
use App\Auth\Domain\User\UserRepository;
use App\Common\Service\Core\Flusher;

class Handler
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly Flusher $flusher,
    ) {
    }

    public function handle(Command $command): Result
    {
        $user = $this->userRepository->findById($command->id);
        if (null === $user) {
            return Result::userNotExists();
        }
        if (null !== $command->email && $command->email !== $user->getEmail() && $this->userRepository->hasByEmail($command->email)) {
            return Result::emailIsBusy();
        }

        $name = $command->name ?? $user->getName();
        $email = $command->email ?? $user->getEmail();

        $user->edit(
            name: $name,
            email: $email
        );

        $this->flusher->flush($user);

        return Result::success($user);
    }
}
