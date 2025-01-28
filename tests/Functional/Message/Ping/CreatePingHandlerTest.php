<?php

declare(strict_types=1);

namespace Heph\Tests\Functional\Message\Ping;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Heph\Entity\Ping\Dto\PingCreateDto;
use Heph\Entity\Ping\Dto\PingDto;
use Heph\Entity\Ping\Ping;
use Heph\Entity\Ping\ValueObject\PingMessage;
use Heph\Entity\Ping\ValueObject\PingStatus;
use Heph\Infrastructure\Mercure\MercurePublish;
use Heph\Message\Command\Ping\CreatePingCommand;
use Heph\Message\Command\Ping\CreatePingHandler;
use Heph\Repository\Ping\PingRepository;
use Heph\Tests\Faker\Dto\Ping\PingCreateDtoFaker;
use Heph\Tests\Functional\HephFunctionalTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

#[
    CoversClass(PingRepository::class),
    CoversClass(Ping::class),
    CoversClass(CreatePingCommand::class),
    CoversClass(PingCreateDto::class),
    CoversClass(PingMessage::class),
    CoversClass(PingStatus::class),
    CoversClass(CreatePingHandler::class),
    CoversClass(MercurePublish::class),
    CoversClass(PingDto::class)
]
class CreatePingHandlerTest extends HephFunctionalTestCase
{
    use InteractsWithMessenger;

    private EntityManagerInterface $entityManager;
    private PingRepository $repository;
    private CreatePingHandler $handler;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        self::bootKernel();
        $this->entityManager = self::getEntityManager();
        $this->entityManager->getConnection()->beginTransaction();

        $this->repository = $this->entityManager->getRepository(Ping::class);
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
    public function testHandlerProcessesMessage(): void
    {
        $bus = self::getContainer()->get('messenger.default_bus');
        $dto = PingCreateDtoFaker::new();
        // $handler = new CreatePingEntityHandler($this->repository);
        $command = new CreatePingCommand($dto);
        // $handler($command);
        $bus->dispatch($command);
        $this->flush();

        // $pings = $this->repository->findAll();

        $this->transport('async')->queue()->assertNotEmpty();
        $m = $this->transport('async')->queue()->messages();
        self::assertInstanceOf(CreatePingCommand::class, $m[0]);
        $this->transport('async')->queue()->assertCount(1);
        $this->transport('async')->process(1);
        $this->transport('async')->queue()->assertCount(0);
    }
}
