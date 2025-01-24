<?php

declare(strict_types=1);

namespace Heph\Tests\Faker\Entity\Ping;

use Heph\Entity\Ping\PingEntity;

final class PingEntityFaker
{
    public static function new(): PingEntity
    {
        return new PingEntity(
            200,
            'Le ping à réussi'
        );
    }
}
