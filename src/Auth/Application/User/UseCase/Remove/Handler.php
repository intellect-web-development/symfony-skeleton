<?php

declare(strict_types=1);

namespace App\Auth\Application\User\UseCase\Remove;

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

        $this->userRepository->remove($user);
        $this->flusher->flush();

        return new Result(
            user: $user,
        );
    }
}
