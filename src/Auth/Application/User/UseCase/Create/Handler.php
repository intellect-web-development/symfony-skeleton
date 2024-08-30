<?php

declare(strict_types=1);

namespace App\Auth\Application\User\UseCase\Create;

use App\Auth\Domain\User\Exception\UserEmailAlreadyTakenException;
use App\Auth\Domain\User\Service\UserNextIdService;
use App\Auth\Domain\User\User;
use App\Auth\Domain\User\UserRepository;
use App\Common\Service\Core\Flusher;
use DateTimeImmutable;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final readonly class Handler
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
        private Flusher $flusher,
        private UserRepository $userRepository,
        private UserNextIdService $userNextIdService,
    ) {
    }

    public function handle(Command $command): Result
    {
        if ($this->userRepository->hasByEmail($command->email)) {
            throw new UserEmailAlreadyTakenException(
                message: "User #{$command->email} already created",
                context: ['email' => $command->email],
            );
        }
        $now = new DateTimeImmutable();
        $user = User::create(
            id: $this->userNextIdService->allocateId(),
            createdAt: $now,
            updatedAt: $now,
            email: $command->email,
            roles: [$command->role],
        );

        $this->userRepository->add($user);
        $user->changePassword(
            $this->passwordHasher->hashPassword($user, $command->plainPassword)
        );

        $this->flusher->flush();

        return new Result(
            user: $user,
        );
    }
}
