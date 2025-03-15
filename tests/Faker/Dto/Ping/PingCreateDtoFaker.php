<?php

declare(strict_types=1);

namespace Heph\Tests\Faker\Dto\Ping;

use Heph\Entity\Ping\Dto\PingCreateDto;

class PingCreateDtoFaker
{
    public static function new(): PingCreateDto
    {
        return new PingCreateDto(
            200,
            'Le ping à réussi en faker'
        );
    }
}
