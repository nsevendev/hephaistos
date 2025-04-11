<?php

declare(strict_types=1);

namespace Heph\Tests\Faker\Dto\Schedule;

use Heph\Entity\Schedule\Dto\ScheduleUpdateDto;

class ScheduleUpdateDtoFaker
{
    public static function new(): ScheduleUpdateDto
    {
        return ScheduleUpdateDto::new('Monday', '09:00', '12:00', '13:00', '17:00');
    }
}
