<?php

declare(strict_types=1);

namespace Heph\Message\Query\Users;

use Heph\Entity\Users\Dto\UsersDto;
use Heph\Entity\Users\Users;
use Heph\Repository\Users\UsersRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetListUsersHandler
{
    public function __construct(private UsersRepository $usersRepository) {}

    /**
     * @return array<int, array<string, mixed>>
     */
    public function __invoke(GetListUsersQuery $query): array
    {
        /** @var Users[] $users */
        $users = $this->usersRepository->findAll();

        $dtoList = UsersDto::toListUsers($users);

        return array_map(static function (UsersDto $dto): array {
            $data = $dto->toArray();
            unset($data['password']); // je shoot la propriété password
            return $data;
        }, $dtoList);
    }
}
