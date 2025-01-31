<?php

declare(strict_types=1);

namespace Heph\Tests\Functional\Repository;

use Doctrine\DBAL\Exception;
use Heph\Entity\Schedule\Schedule;
use Heph\Repository\Schedule\ScheduleRepository;
use Heph\Tests\Faker\Entity\Schedule\ScheduleFaker;
use Heph\Tests\Functional\HephFunctionalTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use ReflectionException;

#[
    CoversClass(ScheduleRepository::class),
    CoversClass(Schedule::class),
]
class ScheduleRepositoryTest extends HephFunctionalTestCase
{
    private ScheduleRepository $scheduleRepository;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->getConnection()->beginTransaction();

        /** @var ScheduleRepository $repository */
        $repository = self::getContainer()->get(ScheduleRepository::class);
        $this->scheduleRepository = $repository;
    }

    /**
     * @throws Exception
     */
    protected function tearDown(): void
    {
        $conn = $this->getEntityManager()->getConnection();

        if ($conn->isTransactionActive()) {
            $conn->rollBack();
        }
    }

    /**
     * @throws ReflectionException
     */
    public function testWeCanPersistAndFindSchedule(): void
    {
        $schedule = ScheduleFaker::new();

        $this->persistAndFlush($schedule);

        /** @var Schedule|null $found */
        $found = $this->scheduleRepository->find($schedule->id());

        self::assertNotNull($found, 'Schedule non trouvé en base alors qu’on vient de le créer');
        self::assertSame('Monday', $found->day());
        self::assertSame('09:00', $found->hoursOpenAm());
        self::assertSame('12:00', $found->hoursCloseAm());
        self::assertSame('13:00', $found->hoursOpenPm());
        self::assertSame('17:00', $found->hoursClosePm());
        self::assertInstanceOf(Schedule::class, $found);
    }

    public function testPersitAndFlushWithRepository(): void
    {
        $schedule = ScheduleFaker::new();

        $this->scheduleRepository->save($schedule);

        /** @var Ping|null $found */
        $found = $this->scheduleRepository->find($schedule->id());

        self::assertNotNull($found, 'Schedule non trouvé en base alors qu’on vient de le créer');
        self::assertSame('Monday', $found->day());
        self::assertSame('09:00', $found->hoursOpenAm());
        self::assertSame('12:00', $found->hoursCloseAm());
        self::assertSame('13:00', $found->hoursOpenPm());
        self::assertSame('17:00', $found->hoursClosePm());
        self::assertInstanceOf(Schedule::class, $found);
    }

    /**
     * @throws ReflectionException
     */
    public function testWeCanUpdateSchedule(): void
    {
        $schedule = ScheduleFaker::new();

        $this->persistAndFlush($schedule);

        $schedule->setDay('Tuesday');
        $schedule->setHoursOpenAm('07:00');
        $schedule->setHoursCloseAm('11:00');
        $schedule->setHoursOpenPm('14:00');
        $schedule->setHoursClosePm('17:30');

        $this->persistAndFlush($schedule);

        /** @var Schedule|null $found */
        $found = $this->scheduleRepository->find($schedule->id());

        self::assertNotNull($found, 'Schedule non trouvé en base alors qu’on vient de le modifier');
        self::assertSame('Tuesday', $found->day());
        self::assertSame('07:00', $found->hoursOpenAm());
        self::assertSame('11:00', $found->hoursCloseAm());
        self::assertSame('14:00', $found->hoursOpenPm());
        self::assertSame('17:30', $found->hoursClosePm());
    }
}
