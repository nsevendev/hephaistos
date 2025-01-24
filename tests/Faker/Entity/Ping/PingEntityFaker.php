<?php

declare(strict_types=1);

namespace Heph\Tests\Faker\Entity\Ping;

use Heph\Entity\Ping\PingEntity;

final class PingEntityFaker
{
    public static function new(): PingEntity
    {
        return new PingEntity(
            status: 200,
            message: 'Le ping à réussi'
        );
    }

    public static function newWithEmptyValues(): PingEntity
    {
        return new PingEntity(
            status: 0,
            message: ''
        );
    }
}
