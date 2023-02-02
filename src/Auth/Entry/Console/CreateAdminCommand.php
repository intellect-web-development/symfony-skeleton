<?php

declare(strict_types=1);

namespace App\Auth\Entry\Console;

use App\Auth\Core\User\Application\UseCase\Create\Command;
use App\Auth\Core\User\Application\UseCase\Create\Handler;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command as CliCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'app:auth:user:create-admin')]
class CreateAdminCommand extends CliCommand
{
    public function __construct(
        private readonly Handler $handler,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Create root user for admin panel')
            ->addOption('email', null, InputOption::VALUE_REQUIRED, 'Email admin', 'admin@dev.com')
            ->addOption('password', null, InputOption::VALUE_REQUIRED, 'Root user password', 'root')
            ->addOption('name', null, InputOption::VALUE_REQUIRED, 'User name', 'Administration');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $email = $input->getOption('email');
        $password = $input->getOption('password');
        $name = $input->getOption('name');

        $this->handler->handle(
            new Command(
                plainPassword: $password,
                name: $name,
                email: $email,
            )
        );

        $io->success('Administration user was created!');

        return CliCommand::SUCCESS;
    }
}
