<?php

namespace Heph\Repository\Schedule;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Heph\Entity\Schedule\Schedule;

/**
 * @extends ServiceEntityRepository<Schedule>
 */
class ScheduleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Schedule::class);
    }

    public function save(Schedule $schedule): void
    {
        $this->getEntityManager()->persist($schedule);
        $this->getEntityManager()->flush();
    }
}
