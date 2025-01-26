<?php

declare(strict_types=1);

namespace Heph\Tests\Functional\Message\Schedule;

use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManagerInterface;
use Heph\Entity\Schedule\Dto\ScheduleCreateDto;
use Heph\Entity\Schedule\Schedule;
use Heph\Entity\Shared\Type\Uid;
use Heph\Entity\Shared\ValueObject\DayValueObject;
use Heph\Entity\Shared\ValueObject\HoursEndValueObject;
use Heph\Entity\Shared\ValueObject\HoursStartValueObject;
use Heph\Infrastructure\Doctrine\Type\UidType;
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
    CoversClass(Uid::class),
    CoversClass(UidType::class),
    CoversClass(CreateScheduleCommand::class),
    CoversClass(ScheduleCreateDto::class),
    CoversClass(DayValueObject::class),
    CoversClass(HoursStartValueObject::class),
    CoversClass(HoursEndValueObject::class),
    CoversClass(CreateScheduleHandler::class),
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

    /**
     * @throws Exception
     */
    public function testDoctrineConfiguration(): void
    {
        $connection = self::getEntityManager()->getConnection();
        self::assertTrue($connection->isConnected(), 'La connexion à la base de données est inactive');

        $isRegistered = Type::hasType('app_uid');
        self::assertTrue($isRegistered, 'Le type personnalisé "app_uid" n\'est pas enregistré dans Doctrine');
    }

    /**
     * @throws Exception
     */
    public function testHandlerProcessesMessage(): void
    {
        $bus = self::getContainer()->get('messenger.default_bus');
        $dto = ScheduleCreateDtoFaker::new();
        // $handler = new CreateScheduleEntityHandler($this->repository);
        $command = new CreateScheduleCommand($dto);
        // $handler($command);
        $bus->dispatch($command);
        $this->flush();

        // $schedules = $this->repository->findAll();

        $this->transport('async')->queue()->assertNotEmpty();
        $m = $this->transport('async')->queue()->messages();
        self::assertInstanceOf(CreateScheduleCommand::class, $m[0]);
        $this->transport()->queue()->assertCount(1);
        $this->transport('async')->process(1);
        $this->transport()->queue()->assertCount(0);
    }
}
