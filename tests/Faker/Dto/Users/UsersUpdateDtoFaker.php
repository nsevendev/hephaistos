<?php

declare(strict_types=1);

namespace Heph\Tests\Faker\Dto\Users;

use Heph\Entity\Users\Dto\UsersUpdateDto;

class UsersUpdateDtoFaker
{
    public static function new(): UsersUpdateDto
    {
        return new UsersUpdateDto(
            'username',
            'password',
            'ROLE_ADMIN',
        );
    }
}
