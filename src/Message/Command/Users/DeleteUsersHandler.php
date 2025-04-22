<?php

declare(strict_types=1);

namespace Heph\Message\Command\Users;

use Heph\Entity\Users\Dto\UsersDeleteDto;
use Heph\Infrastructure\ApiResponse\Exception\Custom\Mercure\MercureInvalidArgumentException;
use Heph\Infrastructure\ApiResponse\Exception\Custom\Users\UsersBadRequestException;
use Heph\Infrastructure\ApiResponse\Exception\Error\Error;
use Heph\Infrastructure\Mercure\MercurePublish;
use Heph\Repository\Users\UsersRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'command.bus')]
class DeleteUsersHandler
{
    public function __construct(private readonly UsersRepository $usersRepository, private readonly MercurePublish $mercurePublish) {}

    /**
     * @return void|null
     *
     * @throws MercureInvalidArgumentException
     * @throws UsersBadRequestException
     */
    public function __invoke(DeleteUsersCommand $command)
    {
        $users = $this->usersRepository->find($command->id);

        if (null === $users) {
            throw new UsersBadRequestException(errors: [Error::create('users', "Aucun users n'a été trouvé")]);
        }

        $usersDto = UsersDeleteDto::fromArray($users);

        $this->usersRepository->remove($users);

        $this->mercurePublish->publish(
            topic: '/users-deleted',
            data: $usersDto->toArray()
        );
    }
}
