<?php

declare(strict_types=1);

namespace Heph\Tests\Functional\Repository;

use Doctrine\DBAL\Exception;
use Heph\Entity\Ping\Ping;
use Heph\Entity\Ping\ValueObject\PingMessage;
use Heph\Entity\Ping\ValueObject\PingStatus;
use Heph\Infrastructure\ApiResponse\Exception\Custom\Ping\PingInvalidArgumentException;
use Heph\Infrastructure\Doctrine\Types\Ping\PingMessageType;
use Heph\Infrastructure\Doctrine\Types\Ping\PingStatusType;
use Heph\Repository\Ping\PingRepository;
use Heph\Tests\Faker\Entity\Ping\PingFaker;
use Heph\Tests\Functional\HephFunctionalTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use ReflectionException;

#[
    CoversClass(PingRepository::class),
    CoversClass(Ping::class),
    CoversClass(PingMessage::class),
    CoversClass(PingMessageType::class),
    CoversClass(PingStatus::class),
    CoversClass(PingStatusType::class),
]
class PingRepositoryTest extends HephFunctionalTestCase
{
    private PingRepository $pingRepository;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $entityManager = self::getEntityManager();
        $entityManager->getConnection()->beginTransaction();

        /** @var PingRepository $repository */
        $repository = self::getContainer()->get(PingRepository::class);
        $this->pingRepository = $repository;
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
     * @throws PingInvalidArgumentException
     */
    public function testWeCanPersistAndFindPing(): void
    {
        $ping = PingFaker::new();

        $this->persistAndFlush($ping);

        /** @var Ping|null $found */
        $found = $this->pingRepository->find($ping->id());

        self::assertNotNull($found, 'PingEntity non trouvé en base alors qu’on vient de le créer');
        self::assertSame(200, $found->status()->value());
        self::assertSame('Le ping à réussi', $found->message()->value());

    }

    public function testPersitAndFlushWithRepository(): void
    {
        $ping = PingFaker::new();

        $this->pingRepository->save($ping);

        /** @var Ping|null $found */
        $found = $this->pingRepository->find($ping->id());

        self::assertNotNull($found, 'PingEntity non trouvé en base alors qu’on vient de le créer');
        self::assertSame(200, $found->status()->value());
        self::assertSame('Le ping à réussi', $found->message()->value());
    }
}
