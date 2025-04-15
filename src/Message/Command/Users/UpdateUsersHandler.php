<?php

declare(strict_types=1);

namespace Heph\Message\Command\Users;

use Doctrine\ORM\EntityManagerInterface;
use Heph\Entity\Users\ValueObject\UsersPassword;
use Heph\Entity\Users\ValueObject\UsersRole;
use Heph\Entity\Users\ValueObject\UsersUsername;
use Heph\Repository\Users\UsersRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsMessageHandler(bus: 'command.bus')]
class UpdateUsersHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UsersRepository $usersRepository,
        private UserPasswordHasherInterface $passwordHasher,
    ) {}

    public function __invoke(UpdateUsersCommand $command): void
    {
        $users = $this->usersRepository->find($command->id);

        if ($users) {
            $dto = $command->usersUpdateDto;
            $plaintextPassword = $command->usersUpdateDto->password;
            $hashedPassword = $this->passwordHasher->hashPassword($users, $plaintextPassword);

            $users->setUsername(UsersUsername::fromValue($dto->username));
            $users->setRole(UsersRole::fromValue($dto->role));
            $users->setPassword(UsersPassword::fromValue($hashedPassword));

            $this->entityManager->persist($users);
            $this->entityManager->flush();
        }
    }
}
