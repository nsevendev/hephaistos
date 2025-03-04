<?php

declare(strict_types=1);

namespace Functional\Message\Ping;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Heph\Controller\Api\Ping\DeletePing;
use Heph\Entity\Ping\Dto\PingPublishDeletedDto;
use Heph\Entity\Ping\Ping;
use Heph\Infrastructure\ApiResponse\Exception\Custom\AbstractApiResponseException;
use Heph\Infrastructure\ApiResponse\Exception\Custom\Ping\PingBadRequestException;
use Heph\Infrastructure\ApiResponse\Exception\Error\Error;
use Heph\Infrastructure\Mercure\MercurePublish;
use Heph\Message\Command\Ping\DeletePingCommand;
use Heph\Message\Command\Ping\DeletePingHandler;
use Heph\Repository\Ping\PingRepository;
use Heph\Tests\Faker\Entity\Ping\PingFaker;
use Heph\Tests\Functional\HephFunctionalTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Uid\Uuid;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

#[
    CoversClass(PingRepository::class),
    CoversClass(Ping::class),
    CoversClass(DeletePing::class),
    CoversClass(DeletePingCommand::class),
    CoversClass(DeletePingHandler::class),
    CoversClass(MercurePublish::class),
    CoversClass(PingPublishDeletedDto::class),
    CoversClass(AbstractApiResponseException::class),
    CoversClass(PingBadRequestException::class),
    CoversClass(Error::class),
]
class DeletePingHandlerTest extends HephFunctionalTestCase
{
    use InteractsWithMessenger;

    private EntityManagerInterface $entityManager;

    private PingRepository $repository;

    private DeletePingHandler $handler;

    // private MercurePublish $mercurePublish;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $this->entityManager = self::getEntityManager();
        $this->entityManager->getConnection()->beginTransaction();

        $this->repository = $this->entityManager->getRepository(Ping::class);
        // $this->mercurePublish = $container->get(MercurePublish::class);
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

    public function testDeletePing(): void
    {
        $ping = PingFaker::new();

        $this->entityManager->persist($ping);
        $this->entityManager->flush();

        $bus = self::getContainer()->get('messenger.default_bus');
        $command = new DeletePingCommand($ping->id()->toString());
        $bus->dispatch($command);
        $this->flush();

        $this->transport('async')->queue()->assertNotEmpty();
        $this->transport('async')->queue()->assertCount(1);
        $this->transport('async')->process(1);
        $this->transport('async')->queue()->assertCount(0);
    }

    public function testDeletePingNotExist(): void
    {
        $this->expectException(HandlerFailedException::class);
        $id = Uuid::v7()->toString();
        $this->transport('othersync')->send(new DeletePingCommand($id));
        $this->transport('othersync')->process(1);
        $this->transport('othersync')->catchExceptions();
    }
}
