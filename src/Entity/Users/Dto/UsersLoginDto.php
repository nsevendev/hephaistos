<?php

declare(strict_types=1);

namespace Heph\Entity\Users\Dto;

use Heph\Entity\Users\Users;

class UsersLoginDto
{
    public function __construct(
        public string $username,
        public string $password,
    ) {}

    public static function fromArray(Users $data): self
    {
        return new self(
            username: $data->username()->value(),
            password: $data->password()->value(),
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'username' => $this->username,
            'password' => $this->password,
        ];
    }
}
