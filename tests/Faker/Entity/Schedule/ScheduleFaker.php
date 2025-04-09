<?php

declare(strict_types=1);

namespace Heph\Tests\Faker\Entity\Schedule;

use Heph\Entity\Schedule\Schedule;
use Heph\Entity\Schedule\ValueObject\ScheduleDay;
use Heph\Entity\Schedule\ValueObject\ScheduleHoursCloseAm;
use Heph\Entity\Schedule\ValueObject\ScheduleHoursClosePm;
use Heph\Entity\Schedule\ValueObject\ScheduleHoursOpenAm;
use Heph\Entity\Schedule\ValueObject\ScheduleHoursOpenPm;
use Heph\Infrastructure\ApiResponse\Exception\Custom\Schedule\ScheduleInvalidArgumentException;

final class ScheduleFaker
{
    /**
     * @throws ScheduleInvalidArgumentException
     */
    public static function new(): Schedule
    {
        return new Schedule(
            day: ScheduleDay::fromValue('Monday'),
            hoursOpenAm: ScheduleHoursOpenAm::fromValue('09:00'),
            hoursCloseAm: ScheduleHoursCloseAm::fromValue('12:00'),
            hoursOpenPm: ScheduleHoursOpenPm::fromValue('13:00'),
            hoursClosePm: ScheduleHoursClosePm::fromValue('17:00'),
        );
    }
}
