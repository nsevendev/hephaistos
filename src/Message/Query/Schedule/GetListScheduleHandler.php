<?php

declare(strict_types=1);

namespace Heph\Message\Query\Schedule;

use Heph\Entity\Schedule\Dto\ScheduleDto;
use Heph\Entity\Schedule\Schedule;
use Heph\Repository\Schedule\ScheduleRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetListScheduleHandler
{
    public function __construct(private ScheduleRepository $scheduleRepository) {}

    /**
     * @return ScheduleDto[]
     */
    public function __invoke(GetListScheduleQuery $query): array
    {
        /** @var Schedule[] $listSchedule */
        $listSchedule = $this->scheduleRepository->findAll();

        return ScheduleDto::toListSchedule($listSchedule);
    }
}
