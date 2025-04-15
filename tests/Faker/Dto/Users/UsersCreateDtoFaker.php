<?php

declare(strict_types=1);

namespace Heph\Tests\Faker\Dto\Users;

use Heph\Entity\Users\Dto\UsersCreateDto;

class UsersCreateDtoFaker
{
    public static function new(): UsersCreateDto
    {
        return new UsersCreateDto(
            'username',
            'password',
            'ROLE_ADMIN',
        );
    }
}
