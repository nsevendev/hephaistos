<?php

declare(strict_types=1);

namespace Heph\Entity\Users\Dto;

class UsersLoginDto
{
    public function __construct(
        public string $username,
        public string $password,
    ) {}
}
