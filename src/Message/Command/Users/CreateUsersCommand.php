<?php

declare(strict_types=1);

namespace Heph\Message\Command\Users;

use Heph\Entity\Users\Dto\UsersCreateDto;

class CreateUsersCommand
{
    public function __construct(
        public UsersCreateDto $usersCreateDto,
    ) {}
}
