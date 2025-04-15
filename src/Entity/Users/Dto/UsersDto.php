<?php

declare(strict_types=1);

namespace Heph\Entity\Users\Dto;

use Heph\Entity\Users\Users;

class UsersDto
{
    public function __construct(
        public string $id,
        public string $username,
        public string $password,
        public string $role,
        public string $createdAt,
        public string $updatedAt,
    ) {}

    public static function fromArray(Users $data): self
    {
        return new self(
            id: (string) $data->id(),
            username: $data->username()->value(),
            password: $data->password()->value(),
            role: $data->role()->value(),
            createdAt: $data->createdAt()->format('Y-m-d H:i:s'),
            updatedAt: $data->updatedAt()->format('Y-m-d H:i:s'),
        );
    }

    /**
     * @param Users[] $data
     *
     * @return UsersDto[]
     */
    public static function toListUsers(array $data): array
    {
        $listUsers = [];

        foreach ($data as $users) {
            $listUsers[] = self::fromArray($users);
        }

        return $listUsers;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'password' => $this->password,
            'role' => $this->role,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
        ];
    }
}
