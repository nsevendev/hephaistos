<?php

declare(strict_types=1);

namespace Heph\Message\Command\Schedule;

use Heph\Entity\Schedule\Dto\ScheduleDto;
use Heph\Entity\Schedule\Schedule;
use Heph\Entity\Schedule\ValueObject\ScheduleDay;
use Heph\Entity\Schedule\ValueObject\ScheduleHoursCloseAm;
use Heph\Entity\Schedule\ValueObject\ScheduleHoursClosePm;
use Heph\Entity\Schedule\ValueObject\ScheduleHoursOpenAm;
use Heph\Entity\Schedule\ValueObject\ScheduleHoursOpenPm;
use Heph\Infrastructure\ApiResponse\Exception\Custom\Mercure\MercureInvalidArgumentException;
use Heph\Infrastructure\ApiResponse\Exception\Custom\Schedule\ScheduleInvalidArgumentException;
use Heph\Infrastructure\Mercure\MercurePublish;
use Heph\Repository\Schedule\ScheduleRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'command.bus')]
readonly class CreateScheduleHandler
{
    public function __construct(
        private ScheduleRepository $scheduleEntityRepository,
        private MercurePublish $mercurePublish,
    ) {}

    /**
     * @throws MercureInvalidArgumentException
     * @throws ScheduleInvalidArgumentException
     */
    public function __invoke(CreateScheduleCommand $command): void
    {
        $schedule = new Schedule(
            day: ScheduleDay::fromValue($command->scheduleEntityCreateDto->day),
            hoursOpenAm: ScheduleHoursOpenAm::fromValue($command->scheduleEntityCreateDto->hoursOpenAm),
            hoursCloseAm: ScheduleHoursCloseAm::fromValue($command->scheduleEntityCreateDto->hoursCloseAm),
            hoursOpenPm: ScheduleHoursOpenPm::fromValue($command->scheduleEntityCreateDto->hoursOpenPm),
            hoursClosePm: ScheduleHoursClosePm::fromValue($command->scheduleEntityCreateDto->hoursClosePm),
        );

        $this->scheduleEntityRepository->save(
            schedule: $schedule
        );

        $scheduleDto = ScheduleDto::fromArray($schedule);

        $this->mercurePublish->publish(
            topic: '/schedule-created',
            data: $scheduleDto->toArray()
        );
    }
}
