<?php

declare(strict_types=1);

namespace Heph\Entity\Users;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Heph\Entity\Users\ValueObject\UsersPassword;
use Heph\Entity\Users\ValueObject\UsersRole;
use Heph\Entity\Users\ValueObject\UsersUsername;
use Heph\Repository\Users\UsersRepository;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: UsersRepository::class)]
class Users implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private Uuid $id;

    #[ORM\Column(type: 'datetime_immutable', name: 'created_at', nullable: false)]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable', name: 'updated_at', nullable: false)]
    private DateTimeImmutable $updatedAt;

    public function __construct(
        #[ORM\Column(name: 'username', type: 'app_users_username', length: 50, nullable: false)]
        private UsersUsername $username,

        #[ORM\Column(name: 'password', type: 'app_users_password', length: 255, nullable: false)]
        private UsersPassword $password,

        #[ORM\Column(name: 'role', type: 'app_users_role', length: 50, nullable: false)]
        private UsersRole $role,
    ) {
        $this->id = Uuid::v7();
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = $this->createdAt;
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function username(): UsersUsername
    {
        return $this->username;
    }

    public function password(): UsersPassword
    {
        return $this->password;
    }

    public function role(): UsersRole
    {
        return $this->role;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUsername(UsersUsername $username): void
    {
        $this->username = $username;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function setPassword(UsersPassword $password): void
    {
        $this->password = $password;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function setRole(UsersRole $role): void
    {
        $this->role = $role;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function setUpdatedAt(DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    // méthods pour le bundle security sinon pas possible de faire les implements
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    public function getRoles(): array
    {
        return [$this->role->value()];
    }

    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function eraseCredentials(): void {}

    public function hasRole(string $role): bool
    {
        return in_array($role, $this->getRoles(), true);
    }
}
