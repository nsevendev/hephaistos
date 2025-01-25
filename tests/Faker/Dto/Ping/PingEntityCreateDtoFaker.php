<?php

declare(strict_types=1);

namespace Heph\Tests\Faker\Dto\Ping;

use Heph\Entity\Ping\Dto\PingEntityCreateDto;
use Heph\Entity\Ping\ValueObject\PingMessage;
use Heph\Entity\Ping\ValueObject\PingStatus;

class PingEntityCreateDtoFaker
{
    public static function new(): PingEntityCreateDto
    {
        return new PingEntityCreateDto(
            PingStatus::fromValue(200),
            PingMessage::fromValue('Le ping à réussi en faker')
        );
    }
}
