<?php

declare(strict_types=1);

namespace Heph\Message\Command\Schedule;

use Doctrine\ORM\EntityManagerInterface;
use Heph\Entity\Schedule\ValueObject\ScheduleDay;
use Heph\Entity\Schedule\ValueObject\ScheduleHoursCloseAm;
use Heph\Entity\Schedule\ValueObject\ScheduleHoursClosePm;
use Heph\Entity\Schedule\ValueObject\ScheduleHoursOpenAm;
use Heph\Entity\Schedule\ValueObject\ScheduleHoursOpenPm;
use Heph\Repository\Schedule\ScheduleRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'command.bus')]
class UpdateScheduleHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ScheduleRepository $scheduleRepository,
    ) {}

    public function __invoke(UpdateScheduleCommand $command): void
    {
        $schedule = $this->scheduleRepository->find($command->id);
        if ($schedule) {
            $schedule->setDay(ScheduleDay::fromValue($command->scheduleUpdateDto->day));
            $schedule->setHoursOpenAm(ScheduleHoursOpenAm::fromValue($command->scheduleUpdateDto->hoursOpenAm));
            $schedule->setHoursCloseAm(ScheduleHoursCloseAm::fromValue($command->scheduleUpdateDto->hoursCloseAm));
            $schedule->setHoursOpenPm(ScheduleHoursOpenPm::fromValue($command->scheduleUpdateDto->hoursOpenPm));
            $schedule->setHoursClosePm(ScheduleHoursClosePm::fromValue($command->scheduleUpdateDto->hoursClosePm));
            $schedule->setUpdatedAt();
            $this->entityManager->persist($schedule);
            $this->entityManager->flush();
        }
    }
}
