<?php

declare(strict_types=1);

namespace Heph\Tests\Faker\Entity\Ping;

use Heph\Entity\Ping\PingEntity;

class PingEntityFaker
{
    public static function new(): PingEntity
    {
        return new PingEntity(
            200,
            "Le ping à réussi"
        );
    }

    public static function newWithNullValues(): PingEntity
    {
        return new PingEntity(
            null,
            null
        );
    }
}
