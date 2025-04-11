<?php

declare(strict_types=1);

namespace Heph\Message\Command\Schedule;

use Heph\Entity\Schedule\Dto\ScheduleCreateDto;

class CreateScheduleCommand
{
    public function __construct(
        public ScheduleCreateDto $scheduleEntityCreateDto,
    ) {}
}
