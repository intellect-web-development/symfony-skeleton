<?php

declare(strict_types=1);

namespace App\Auth\Domain\User;

use App\Auth\Domain\User\ValueObject\Id;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, $em->getClassMetadata(User::class));
    }

    public function add(User $user): void
    {
        $this->getEntityManager()->persist($user);
    }

    public function remove(User $user): void
    {
        $this->getEntityManager()->remove($user);
    }

    public function findById(Id $id): ?User
    {
        /** @var User|null $user */
        $user = $this->findOneBy(['id' => $id]);

        return $user;
    }

    public function nextId(): Id
    {
        $id = $this->getEntityManager()
            ->getConnection()
            ->prepare("SELECT nextval('auth_user_id_seq')")
            ->executeQuery()
            ->fetchOne();

        return new Id((string) $id);
    }

    public function hasByEmail(string $email): bool
    {
        return null !== $this->findOneBy(['email' => $email]);
    }

    public function findByEmail(string $email): ?User
    {
        /** @var User|null $user */
        $user = $this->findOneBy(['email' => $email]);

        return $user;
    }
}
