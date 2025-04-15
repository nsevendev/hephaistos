<?php

declare(strict_types=1);

namespace Heph\Message\Query\Users;

use Heph\Entity\Users\Dto\UsersGetOneByIdDto;
use Heph\Entity\Users\Users;
use Heph\Repository\Users\UsersRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetFirstUsersHandler
{
    public function __construct(private UsersRepository $usersRepository) {}

    public function __invoke(GetUsersByIdQuery $query): ?UsersGetOneByIdDto
    {
        /** @var Users|null $users */
        $users = $this->usersRepository->find($query->id);

        return $users ? UsersGetOneByIdDto::fromArray($users) : null;
    }
}
