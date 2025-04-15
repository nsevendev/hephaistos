<?php

declare(strict_types=1);

namespace Heph\Entity\Users\Dto;

use Heph\Entity\Users\Users;

class UsersGetOneByIdDto
{
    public function __construct(
        public string $id,
        public string $username,
        public string $role,
        public string $createdAt,
        public string $updatedAt,
    ) {}

    public static function fromArray(Users $data): self
    {
        return new self(
            id: (string) $data->id(),
            username: $data->username()->value(),
            role: $data->role()->value(),
            createdAt: $data->createdAt()->format('Y-m-d H:i:s'),
            updatedAt: $data->updatedAt()->format('Y-m-d H:i:s'),
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'role' => $this->role,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
        ];
    }
}
