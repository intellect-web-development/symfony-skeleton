<?php

declare(strict_types=1);

namespace App\Auth\Entry\Console\CreateAdmin;

use App\Auth\Application\User\UseCase\Create\Command;
use App\Auth\Application\User\UseCase\Create\Handler;
use App\Auth\Domain\User\Exception\UserEmailAlreadyTakenException;
use App\Auth\Domain\User\User;
use App\Common\Exception\Domain\DomainException;
use IWD\SymfonyEntryContract\Service\CliContractResolver;
use IWD\SymfonyEntryContract\Attribute\CliContract;
use IWD\SymfonyEntryContract\Console\CliCommand;
use IWD\SymfonyEntryContract\Interfaces\InputContractInterface;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'app:auth:user:create-admin',
    description: 'Create root user for admin panel',
)]
#[CliContract(class: InputContract::class)]
class CreateAdminCommand extends CliCommand
{
    public function __construct(
        private readonly Handler $handler,
        CliContractResolver $cliContractResolver,
    ) {
        parent::__construct($cliContractResolver);
    }

    /**
     * @param InputContract $inputContract
     */
    protected function handle(InputContractInterface $inputContract): int
    {
        try {
            $result = $this->handler->handle(
                new Command(
                    email: $inputContract->email,
                    plainPassword: $inputContract->password,
                    role: User::ROLE_ADMIN,
                )
            );
        } catch (DomainException $domainException) {
        } finally {
            $result ??= null;
            $domainException ??= null;
        }

        if (null !== $result) {
            $this->io->success('Administration user was created!');

            return self::SUCCESS;
        }
        if ($domainException instanceof UserEmailAlreadyTakenException) {
            $this->io->error('Email already taken');

            return self::FAILURE;
        }

        $this->io->error('Unexpected error');

        return self::FAILURE;
    }
}
