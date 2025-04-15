<?php

declare(strict_types=1);

namespace Heph\Message\Query\Users;

use Heph\Entity\Users\Dto\UsersLoginDto;

class LoginUsersQuery
{
    public function __construct(
        public readonly UsersLoginDto $usersLoginDto,
    ) {}
}
