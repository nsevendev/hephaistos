<?php

declare(strict_types=1);

namespace Heph\Tests\Faker\Dto\Schedule;

use Heph\Entity\Schedule\Dto\ScheduleCreateDto;

class ScheduleCreateDtoFaker
{
    public static function new(): ScheduleCreateDto
    {
        return new ScheduleCreateDto(
            'Monday',
            '08:00',
            '12:00',
            '13:00',
            '18:00',
        );
    }
}
