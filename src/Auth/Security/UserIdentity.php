<?php

declare(strict_types=1);

namespace App\Auth\Security;

use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserIdentity implements UserInterface, EquatableInterface
{
    private string $id;
    private string $username;
    private string $password;
    private string $display;
    private string $role;

    public function __construct(
        string $id,
        string $username,
        string $password,
        string $display,
        string $role,
    ) {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->display = $display;
        $this->role = $role;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getDisplay(): string
    {
        return $this->display;
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getRoles(): array
    {
        return [$this->role];
    }

    public function eraseCredentials(): void
    {
    }

    public function isEqualTo(UserInterface $user): bool
    {
        if (!$user instanceof self) {
            return false;
        }

        return
            $this->id === $user->id &&
            $this->password === $user->password &&
            $this->role === $user->role;
    }
}
