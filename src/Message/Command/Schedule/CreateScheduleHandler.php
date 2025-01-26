<?php

declare(strict_types=1);

namespace Heph\Message\Command\Schedule;

use Heph\Entity\Schedule\Schedule;
use Heph\Repository\Schedule\ScheduleRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'command.bus')]
readonly class CreateScheduleHandler
{
    public function __construct(
        private ScheduleRepository $scheduleRepository,
    ) {}

    public function __invoke(CreateScheduleCommand $command): void
    {
        $schedule = new Schedule(
            day: $command->scheduleEntityCreateDto->day()->value(),
            hoursStart: $command->scheduleEntityCreateDto->hours_start()->value(),
            hoursEnd: $command->scheduleEntityCreateDto->hours_end()->value()
        );

        $this->scheduleRepository->save(
            schedule: $schedule
        );
    }
}
