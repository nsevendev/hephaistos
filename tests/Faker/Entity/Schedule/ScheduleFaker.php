<?php

declare(strict_types=1);

namespace Heph\Tests\Faker\Entity\Schedule;

use Heph\Entity\Schedule\Schedule;

final class ScheduleFaker
{
    public static function new(): Schedule
    {
        return new Schedule(
            day: 'Monday',
            hoursStart: '09:00',
            hoursEnd: '17:00',
        );
    }

    public static function newWithEmptyValues(): Schedule
    {
        return new Schedule(
            day: '',
            hoursStart: '',
            hoursEnd: '',
        );
    }
}
