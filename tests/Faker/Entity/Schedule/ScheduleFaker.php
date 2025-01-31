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
            hoursOpenAm: '09:00',
            hoursCloseAm: '12:00',
            hoursOpenPm: '13:00',
            hoursClosePm: '17:00',
        );
    }
}