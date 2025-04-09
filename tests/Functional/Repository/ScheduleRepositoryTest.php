<?php

declare(strict_types=1);

namespace Heph\Tests\Functional\Repository;

use Doctrine\DBAL\Exception;
use Heph\Entity\Schedule\Schedule;
use Heph\Entity\Schedule\ValueObject\ScheduleDay;
use Heph\Entity\Schedule\ValueObject\ScheduleHoursCloseAm;
use Heph\Entity\Schedule\ValueObject\ScheduleHoursClosePm;
use Heph\Entity\Schedule\ValueObject\ScheduleHoursOpenAm;
use Heph\Entity\Schedule\ValueObject\ScheduleHoursOpenPm;
use Heph\Entity\Shared\ValueObject\DescriptionValueObject;
use Heph\Entity\Shared\ValueObject\LibelleValueObject;
use Heph\Infrastructure\Doctrine\Types\Schedule\ScheduleDayType;
use Heph\Infrastructure\Doctrine\Types\Schedule\ScheduleHoursCloseAmType;
use Heph\Infrastructure\Doctrine\Types\Schedule\ScheduleHoursClosePmType;
use Heph\Infrastructure\Doctrine\Types\Schedule\ScheduleHoursOpenAmType;
use Heph\Infrastructure\Doctrine\Types\Schedule\ScheduleHoursOpenPmType;
use Heph\Infrastructure\Doctrine\Types\Shared\DescriptionType;
use Heph\Infrastructure\Doctrine\Types\Shared\LibelleType;
use Heph\Repository\Schedule\ScheduleRepository;
use Heph\Tests\Faker\Entity\Schedule\ScheduleFaker;
use Heph\Tests\Functional\HephFunctionalTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use ReflectionException;

#[
    CoversClass(ScheduleRepository::class),
    CoversClass(Schedule::class),
    CoversClass(LibelleValueObject::class),
    CoversClass(DescriptionValueObject::class),
    CoversClass(LibelleType::class),
    CoversClass(DescriptionType::class),
    CoversClass(ScheduleDay::class),
    CoversClass(ScheduleHoursCloseAm::class),
    CoversClass(ScheduleHoursClosePm::class),
    CoversClass(ScheduleHoursOpenAm::class),
    CoversClass(ScheduleHoursOpenPm::class),
    CoversClass(ScheduleDayType::class),
    CoversClass(ScheduleHoursCloseAmType::class),
    CoversClass(ScheduleHoursClosePmType::class),
    CoversClass(ScheduleHoursOpenAmType::class),
    CoversClass(ScheduleHoursOpenPmType::class),
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
        self::assertSame('Monday', $found->day()->value());
        self::assertSame('09:00', $found->hoursOpenAm()->value());
        self::assertSame('12:00', $found->hoursCloseAm()->value());
        self::assertSame('13:00', $found->hoursOpenPm()->value());
        self::assertSame('17:00', $found->hoursClosePm()->value());
        self::assertInstanceOf(Schedule::class, $found);
    }

    public function testPersitAndFlushWithRepository(): void
    {
        $schedule = ScheduleFaker::new();

        $this->scheduleRepository->save($schedule);

        /** @var Ping|null $found */
        $found = $this->scheduleRepository->find($schedule->id());

        self::assertNotNull($found, 'Schedule non trouvé en base alors qu’on vient de le créer');
        self::assertSame('Monday', $found->day()->value());
        self::assertSame('09:00', $found->hoursOpenAm()->value());
        self::assertSame('12:00', $found->hoursCloseAm()->value());
        self::assertSame('13:00', $found->hoursOpenPm()->value());
        self::assertSame('17:00', $found->hoursClosePm()->value());
        self::assertInstanceOf(Schedule::class, $found);
    }

    /**
     * @throws ReflectionException
     */
    public function testWeCanUpdateSchedule(): void
    {
        $schedule = ScheduleFaker::new();

        $this->persistAndFlush($schedule);

        $schedule->setDay(new ScheduleDay('Tuesday'));
        $schedule->setHoursOpenAm(new ScheduleHoursOpenAm('07:00'));
        $schedule->setHoursCloseAm(new ScheduleHoursCloseAm('11:00'));
        $schedule->setHoursOpenPm(new ScheduleHoursOpenPm('14:00'));
        $schedule->setHoursClosePm(new ScheduleHoursClosePm('17:30'));

        $this->persistAndFlush($schedule);

        /** @var Schedule|null $found */
        $found = $this->scheduleRepository->find($schedule->id());

        self::assertNotNull($found, 'Schedule non trouvé en base alors qu’on vient de le modifier');
        self::assertSame('Tuesday', $found->day()->value());
        self::assertSame('07:00', $found->hoursOpenAm()->value());
        self::assertSame('11:00', $found->hoursCloseAm()->value());
        self::assertSame('14:00', $found->hoursOpenPm()->value());
        self::assertSame('17:30', $found->hoursClosePm()->value());
    }
}
