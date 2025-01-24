<?php

declare(strict_types=1);

namespace Heph\Tests\Functional\Repository;

use Doctrine\DBAL\Exception;
use Heph\Entity\Schedule\Schedule;
use Heph\Entity\Shared\Type\Uid;
use Heph\Infrastructure\Doctrine\Type\UidType;
use Heph\Repository\Schedule\ScheduleRepository;
use Heph\Tests\Faker\Entity\Schedule\ScheduleFaker;
use Heph\Tests\Functional\HephFunctionalTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use ReflectionException;

#[
    CoversClass(ScheduleRepository::class),
    CoversClass(Schedule::class),
    CoversClass(Uid::class),
    CoversClass(UidType::class)
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
        self::assertSame('09:00', $found->hoursStart());
        self::assertSame('17:00', $found->hoursEnd());
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
        $schedule->setHoursStart('08:00');
        $schedule->setHoursEnd('16:00');

        $this->persistAndFlush($schedule);

        /** @var Schedule|null $found */
        $found = $this->scheduleRepository->find($schedule->id());

        self::assertNotNull($found, 'Schedule non trouvé en base alors qu’on vient de le modifier');
        self::assertSame('Tuesday', $found->day());
        self::assertSame('08:00', $found->hoursStart());
        self::assertSame('16:00', $found->hoursEnd());
    }
}
