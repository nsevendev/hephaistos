<?php

declare(strict_types=1);

namespace Heph\Tests\Functional\Message\Schedule;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Heph\Entity\Schedule\Dto\ScheduleUpdateDto;
use Heph\Entity\Schedule\Schedule;
use Heph\Entity\Schedule\ValueObject\ScheduleDay;
use Heph\Entity\Schedule\ValueObject\ScheduleHoursCloseAm;
use Heph\Entity\Schedule\ValueObject\ScheduleHoursClosePm;
use Heph\Entity\Schedule\ValueObject\ScheduleHoursOpenAm;
use Heph\Entity\Schedule\ValueObject\ScheduleHoursOpenPm;
use Heph\Infrastructure\Doctrine\Types\Schedule\ScheduleDayType;
use Heph\Infrastructure\Doctrine\Types\Schedule\ScheduleHoursCloseAmType;
use Heph\Infrastructure\Doctrine\Types\Schedule\ScheduleHoursClosePmType;
use Heph\Infrastructure\Doctrine\Types\Schedule\ScheduleHoursOpenAmType;
use Heph\Infrastructure\Doctrine\Types\Schedule\ScheduleHoursOpenPmType;
use Heph\Message\Command\Schedule\UpdateScheduleCommand;
use Heph\Message\Command\Schedule\UpdateScheduleHandler;
use Heph\Repository\Schedule\ScheduleRepository;
use Heph\Tests\Faker\Dto\Schedule\ScheduleUpdateDtoFaker;
use Heph\Tests\Faker\Entity\Schedule\ScheduleFaker;
use Heph\Tests\Functional\HephFunctionalTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

#[
    CoversClass(ScheduleRepository::class),
    CoversClass(Schedule::class),
    CoversClass(UpdateScheduleCommand::class),
    CoversClass(UpdateScheduleHandler::class),
    CoversClass(ScheduleUpdateDto::class),
    CoversClass(ScheduleDay::class),
    CoversClass(ScheduleHoursOpenAm::class),
    CoversClass(ScheduleHoursCloseAm::class),
    CoversClass(ScheduleHoursOpenPm::class),
    CoversClass(ScheduleHoursClosePm::class),
    CoversClass(ScheduleDayType::class),
    CoversClass(ScheduleHoursOpenAmType::class),
    CoversClass(ScheduleHoursCloseAmType::class),
    CoversClass(ScheduleHoursOpenPmType::class),
    CoversClass(ScheduleHoursClosePmType::class),
]
class UpdateScheduleHandlerTest extends HephFunctionalTestCase
{
    use InteractsWithMessenger;

    private EntityManagerInterface $entityManager;
    private ScheduleRepository $repository;
    private UpdateScheduleHandler $handler;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        self::bootKernel();
        $this->entityManager = self::getEntityManager();
        $this->entityManager->getConnection()->beginTransaction();

        $this->repository = $this->entityManager->getRepository(Schedule::class);
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
     * @throws Exception
     */
    public function testDoctrineConfiguration(): void
    {
        $connection = self::getEntityManager()->getConnection();
        self::assertTrue($connection->isConnected(), 'La connexion à la base de données est inactive');
    }

    /**
     * @throws Exception
     */
    public function testUpdateSchedule(): void
    {
        $shedule = ScheduleFaker::new();
        $this->entityManager->persist($shedule);
        $this->entityManager->flush();

        $firstSchedule = $this->repository->findOneBy([]);
        self::assertNotNull($firstSchedule, 'Entity non trouvée en bdd.');
        self::assertEquals('Monday', $firstSchedule->day());
        self::assertEquals('09:00', $firstSchedule->hoursOpenAm());
        self::assertEquals('12:00', $firstSchedule->hoursCloseAm());
        self::assertEquals('13:00', $firstSchedule->hoursOpenPm());
        self::assertEquals('17:00', $firstSchedule->hoursClosePm());

        $bus = self::getContainer()->get('messenger.default_bus');
        $dto = ScheduleUpdateDtoFaker::new();
        $command = new UpdateScheduleCommand($dto, (string) $firstSchedule->id());
        $bus->dispatch($command);
        $this->flush();

        $this->transport('async')->queue()->assertNotEmpty();
        $this->transport('async')->queue()->assertCount(1);
        $this->transport('async')->process(1);
        $this->transport('async')->queue()->assertCount(0);

        $updatedSchedule = $this->repository->findOneBy([]);
        self::assertNotNull($updatedSchedule, 'Entity non trouvée en bdd.');

        self::assertEquals($dto->day, $updatedSchedule->day(), 'Le day ne correspond pas au dto.');
        self::assertEquals($dto->hoursOpenAm, $updatedSchedule->hoursOpenAm(), 'Le hoursOpenAm ne correspond pas au dto.');
        self::assertEquals($dto->hoursCloseAm, $updatedSchedule->hoursCloseAm(), 'Le hoursCloseAm ne correspond pas au dto.');
        self::assertEquals($dto->hoursOpenPm, $updatedSchedule->hoursOpenPm(), 'Le hoursOpenPm ne correspond pas au dto.');
        self::assertEquals($dto->hoursClosePm, $updatedSchedule->hoursClosePm(), 'Le hoursClosePm ne correspond pas au dto.');
    }
}
