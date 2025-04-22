<?php

declare(strict_types=1);

namespace Heph\Message\Command\Users;

use Heph\Entity\Users\Dto\UsersUpdateDto;

class UpdateUsersCommand
{
    public function __construct(
        public readonly UsersUpdateDto $usersUpdateDto,
        public readonly string $id,
    ) {}
}
