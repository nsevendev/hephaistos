<?php

declare(strict_types=1);

namespace Heph\Tests\Functional\Message\Schedule;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Heph\Entity\Schedule\Dto\ScheduleCreateDto;
use Heph\Entity\Schedule\Dto\ScheduleDto;
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
use Heph\Infrastructure\Mercure\MercurePublish;
use Heph\Message\Command\Schedule\CreateScheduleCommand;
use Heph\Message\Command\Schedule\CreateScheduleHandler;
use Heph\Repository\Schedule\ScheduleRepository;
use Heph\Tests\Faker\Dto\Schedule\ScheduleCreateDtoFaker;
use Heph\Tests\Functional\HephFunctionalTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

#[
    CoversClass(ScheduleRepository::class),
    CoversClass(Schedule::class),
    CoversClass(CreateScheduleCommand::class),
    CoversClass(ScheduleCreateDto::class),
    CoversClass(ScheduleDay::class),
    CoversClass(ScheduleHoursOpenAm::class),
    CoversClass(ScheduleHoursCloseAm::class),
    CoversClass(ScheduleHoursOpenPm::class),
    CoversClass(ScheduleHoursClosePm::class),
    CoversClass(CreateScheduleHandler::class),
    CoversClass(MercurePublish::class),
    CoversClass(ScheduleDto::class),
    CoversClass(ScheduleDayType::class),
    CoversClass(ScheduleHoursOpenAmType::class),
    CoversClass(ScheduleHoursCloseAmType::class),
    CoversClass(ScheduleHoursOpenPmType::class),
    CoversClass(ScheduleHoursClosePmType::class),
]
class CreateScheduleHandlerTest extends HephFunctionalTestCase
{
    use InteractsWithMessenger;

    private EntityManagerInterface $entityManager;
    private ScheduleRepository $repository;
    private CreateScheduleHandler $handler;

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

    public function testDoctrineConfiguration(): void
    {
        $connection = self::getEntityManager()->getConnection();
        self::assertTrue($connection->isConnected(), 'La connexion à la base de données est inactive');
    }

    /**
     * @throws Exception
     */
    public function testHandlerProcessesMessage(): void
    {
        $bus = self::getContainer()->get('messenger.default_bus');
        $dto = ScheduleCreateDtoFaker::new();
        $command = new CreateScheduleCommand($dto);
        $bus->dispatch($command);
        $this->flush();

        $this->transport('async')->queue()->assertNotEmpty();
        $m = $this->transport('async')->queue()->messages();
        self::assertInstanceOf(CreateScheduleCommand::class, $m[0]);
        $this->transport('async')->queue()->assertCount(1);
        $this->transport('async')->process(1);
        $this->transport('async')->queue()->assertCount(0);
    }
}
