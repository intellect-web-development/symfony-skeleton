<?php

declare(strict_types=1);

namespace App\Auth\Application\User\UseCase\Edit;

use App\Auth\Domain\User\Exception\UserEmailAlreadyTakenException;
use App\Auth\Domain\User\Exception\UserNotFoundException;
use App\Auth\Domain\User\UserRepository;
use App\Common\Service\Core\Flusher;

final readonly class Handler
{
    public function __construct(
        private Flusher $flusher,
        private UserRepository $userRepository,
    ) {
    }

    public function handle(Command $command): Result
    {
        $user = $this->userRepository->findById($command->id);
        if (null === $user) {
            throw new UserNotFoundException(
                message: "User #{$command->id} not found",
                context: ['id' => $command->id],
            );
        }

        if (null !== $command->email && $command->email !== $user->getEmail() && $this->userRepository->hasByEmail($command->email)) {
            throw new UserEmailAlreadyTakenException(
                message: "User #{$command->email} not found",
                context: ['email' => $command->email],
            );
        }

        $email = $command->email ?? $user->getEmail();
        $role = $command->role ?? $user->getRole();

        $user->edit(
            email: $email,
            roles: [$role]
        );

        $this->flusher->flush();

        return new Result(
            user: $user,
        );
    }
}
