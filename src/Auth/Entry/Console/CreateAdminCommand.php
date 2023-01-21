<?php

declare(strict_types=1);

namespace App\Auth\Entry\Console;

use App\Auth\Core\User\Domain\User;
use App\Auth\Core\User\Domain\UserRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(name: 'app:auth:user:create-admin')]
class CreateAdminCommand extends Command
{
    private EntityManagerInterface $em;
    private UserPasswordHasherInterface $passwordEncoder;
    private UserRepository $userRepository;

    public function __construct(
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordEncoder,
        UserRepository $userRepository,
    ) {
        parent::__construct();

        $this->em = $em;
        $this->passwordEncoder = $passwordEncoder;
        $this->userRepository = $userRepository;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Create root user for admin panel')
            ->addArgument('email', InputArgument::REQUIRED, 'Root user email')
            ->addArgument('password', InputArgument::OPTIONAL, 'Root user password');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');

        if (!$password) {
            $password = bin2hex(random_bytes(8));
            $io->note(sprintf('Using generated password: %s', $password));
        }

        if ($this->userRepository->findOneBy(['email' => $email])) {
            $io->error('Root user is already exists');

            return 1;
        }

        $now = new DateTimeImmutable();
        $user = User::create(
            id: $this->userRepository->nextId(),
            createdAt: $now,
            updatedAt: $now,
            email: $email,
            roles: [User::ROLE_ADMIN],
            name: 'Administration'
        );
        $user->changePassword(
            $this->passwordEncoder->hashPassword($user, $password)
        );

        $this->em->persist($user);
        $this->em->flush();

        $io->success('Administration user was created!');

        return 0;
    }
}
