<?php

declare(strict_types=1);

namespace Heph\Message\Command\Schedule;

use Heph\Entity\Schedule\Dto\ScheduleUpdateDto;

class UpdateScheduleCommand
{
    public function __construct(
        public readonly ScheduleUpdateDto $engineRemapUpdateDto,
        public readonly string $id,
    ) {}
}
