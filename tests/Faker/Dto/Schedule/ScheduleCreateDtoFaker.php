<?php

declare(strict_types=1);

namespace Heph\Tests\Faker\Dto\Schedule;

use Heph\Entity\Schedule\Dto\ScheduleCreateDto;
use Heph\Entity\Shared\ValueObject\DayValueObject;
use Heph\Entity\Shared\ValueObject\HoursCloseAmValueObject;
use Heph\Entity\Shared\ValueObject\HoursClosePmValueObject;
use Heph\Entity\Shared\ValueObject\HoursOpenAmValueObject;
use Heph\Entity\Shared\ValueObject\HoursOpenPmValueObject;

class ScheduleCreateDtoFaker
{
    public static function new(): ScheduleCreateDto
    {
        return new ScheduleCreateDto(
            day: DayValueObject::fromValue('Monday'),
            hoursOpenAm: HoursOpenAmValueObject::fromValue('08:00'),
            hoursCloseAm: HoursCloseAmValueObject::fromValue('12:00'),
            hoursOpenPm: HoursOpenPmValueObject::fromValue('13:00'),
            hoursClosePm: HoursClosePmValueObject::fromValue('18:00'),
        );
    }
}
