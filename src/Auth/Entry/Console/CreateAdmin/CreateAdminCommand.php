<?php

declare(strict_types=1);

namespace App\Auth\Entry\Console\CreateAdmin;

use App\Auth\Core\User\Application\UseCase\Create\Command;
use App\Auth\Core\User\Application\UseCase\Create\Handler;
use IWD\Symfony\PresentationBundle\Console\CliCommand;
use IWD\Symfony\PresentationBundle\Interfaces\InputContractInterface;
use IWD\Symfony\PresentationBundle\Service\CliContractResolver;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:auth:user:create-admin',
    description: 'Create root user for admin panel',
)]
class CreateAdminCommand extends CliCommand
{
    public function __construct(
        private readonly Handler $handler,
        CliContractResolver $cliContractResolver,
    ) {
        parent::__construct($cliContractResolver);
    }

    protected static function getInputContractClass(): string
    {
        return InputContract::class;
    }

    /**
     * @psalm-suppress MoreSpecificImplementedParamType
     *
     * @param InputContract $inputContract
     */
    protected function handle(SymfonyStyle $io, InputContractInterface $inputContract): int
    {
        $this->handler->handle(
            new Command(
                plainPassword: $inputContract->password,
                name: $inputContract->name,
                email: $inputContract->email,
            )
        );

        $io->success('Administration user was created!');

        return CliCommand::SUCCESS;
    }
}
