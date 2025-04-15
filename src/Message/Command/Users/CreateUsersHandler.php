<?php

declare(strict_types=1);

namespace Heph\Message\Command\Users;

use Doctrine\ORM\EntityManagerInterface;
use Heph\Entity\Users\Dto\UsersDto;
use Heph\Entity\Users\Users;
use Heph\Entity\Users\ValueObject\UsersPassword;
use Heph\Entity\Users\ValueObject\UsersRole;
use Heph\Entity\Users\ValueObject\UsersUsername;
use Heph\Infrastructure\ApiResponse\Exception\Custom\Mercure\MercureInvalidArgumentException;
use Heph\Infrastructure\Mercure\MercurePublish;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsMessageHandler(bus: 'command.bus')]
readonly class CreateUsersHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private MercurePublish $mercurePublish,
        private UserPasswordHasherInterface $passwordHasher,
    ) {}

    /**
     * @throws MercureInvalidArgumentException
     */
    public function __invoke(CreateUsersCommand $command): void
    {

        $users = new Users(
            username: UsersUsername::fromValue($command->usersCreateDto->username),
            password: UsersPassword::fromValue($command->usersCreateDto->password),
            role: UsersRole::fromValue($command->usersCreateDto->role)
        );

        $plaintextPassword = $command->usersCreateDto->password;

        $hashedPassword = $this->passwordHasher->hashPassword($users, $plaintextPassword);
        $users->setPassword(UsersPassword::fromValue($hashedPassword));

        $this->entityManager->persist($users);
        $this->entityManager->flush();

        $usersDto = UsersDto::fromArray($users);

        $this->mercurePublish->publish(
            topic: '/users-created',
            data: $usersDto->toArray()
        );
    }
}
