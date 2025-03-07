<?php

declare(strict_types=1);

namespace Heph\Tests\Faker\Dto\Ping;

use Heph\Entity\Ping\Dto\PingCreateDto;
use Heph\Entity\Ping\ValueObject\PingMessage;
use Heph\Entity\Ping\ValueObject\PingStatus;

class PingCreateDtoFaker
{
    public static function new(): PingCreateDto
    {
        return new PingCreateDto(
            PingStatus::fromValue(200),
            PingMessage::fromValue('Le ping à réussi en faker')
        );
    }
}
