<?php

declare(strict_types=1);

namespace Heph\Tests\Faker\Dto\Schedule;

use Heph\Entity\Schedule\Dto\ScheduleCreateDto;
use Heph\Entity\Shared\ValueObject\ScheduleDay;
use Heph\Entity\Shared\ValueObject\ScheduleHoursEnd;
use Heph\Entity\Shared\ValueObject\ScheduleHoursStart;

class ScheduleCreateDtoFaker
{
    public static function new(): ScheduleCreateDto
    {
        return new ScheduleCreateDto(
            ScheduleDay::fromValue('Monday'),
            ScheduleHoursStart::fromValue('08:00'),
            ScheduleHoursEnd::fromValue('18:00')
        );
    }
}
