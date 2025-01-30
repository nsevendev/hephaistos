<?php

declare(strict_types=1);

namespace Heph\Tests\Faker\Entity\Ping;

use Heph\Entity\Ping\Ping;

final class PingFaker
{
    public static function new(): Ping
    {
        return new Ping(
            status: 200,
            message: 'Le ping à réussi'
        );
    }
}
