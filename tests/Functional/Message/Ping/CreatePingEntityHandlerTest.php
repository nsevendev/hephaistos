<?php

declare(strict_types=1);

namespace Heph\Tests\Functional\Message\Ping;

use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManagerInterface;
use Heph\Entity\Ping\Dto\PingEntityCreateDto;
use Heph\Entity\Ping\PingEntity;
use Heph\Entity\Ping\ValueObject\PingMessage;
use Heph\Entity\Ping\ValueObject\PingStatus;
use Heph\Entity\Shared\Type\Uid;
use Heph\Infrastructure\Doctrine\Type\UidType;
use Heph\Message\Command\Ping\CreatePingEntityCommand;
use Heph\Message\Command\Ping\CreatePingEntityHandler;
use Heph\Repository\Ping\PingEntityRepository;
use Heph\Tests\Faker\Dto\Ping\PingEntityCreateDtoFaker;
use Heph\Tests\Functional\HephFunctionalTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Transport\InMemory\InMemoryTransport;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

#[
    CoversClass(PingEntityRepository::class),
    CoversClass(PingEntity::class),
    CoversClass(Uid::class),
    CoversClass(UidType::class),
    CoversClass(CreatePingEntityCommand::class),
    CoversClass(PingEntityCreateDto::class),
    CoversClass(PingMessage::class),
    CoversClass(PingStatus::class),
    CoversClass(CreatePingEntityHandler::class),
]
class CreatePingEntityHandlerTest extends HephFunctionalTestCase
{
    use InteractsWithMessenger;

    private EntityManagerInterface $entityManager;
    private PingEntityRepository $repository;
    private CreatePingEntityHandler $handler;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        self::bootKernel();
        $this->entityManager = self::getEntityManager();
        $this->entityManager->getConnection()->beginTransaction();

        $this->repository = $this->entityManager->getRepository(PingEntity::class);
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
        $dto = PingEntityCreateDtoFaker::new();
        //$handler = new CreatePingEntityHandler($this->repository);
        $command = new CreatePingEntityCommand($dto);
        // $handler($command);
        $bus->dispatch($command);
        $this->flush();

        //$pings = $this->repository->findAll();

        $this->transport('async')->queue()->assertNotEmpty();
        $m = $this->transport('async')->queue()->messages();
        self::assertInstanceOf(CreatePingEntityCommand::class, $m[0]);
        $this->transport()->queue()->assertCount(1);
        $this->transport('async')->process(1);
        $this->transport()->queue()->assertCount(0);
    }
}
