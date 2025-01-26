<?php

declare(strict_types=1);

namespace Heph\Tests\Faker\Dto\Schedule;

use Heph\Entity\Schedule\Dto\ScheduleCreateDto;
use Heph\Entity\Shared\ValueObject\DayValueObject;
use Heph\Entity\Shared\ValueObject\HoursEndValueObject;
use Heph\Entity\Shared\ValueObject\HoursStartValueObject;

class ScheduleCreateDtoFaker
{
    public static function new(): ScheduleCreateDto
    {
        return new ScheduleCreateDto(
            DayValueObject::fromValue('Monday'),
            HoursStartValueObject::fromValue('08:00'),
            HoursEndValueObject::fromValue('18:00')
        );
    }
}
