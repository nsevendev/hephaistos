<?php

declare(strict_types=1);

namespace Heph\Tests\Functional\Repository;

use Doctrine\DBAL\Exception;
use Heph\Entity\Ping\PingEntity;
use Heph\Entity\Shared\Type\Uid;
use Heph\Infrastructure\Doctrine\Type\UidType;
use Heph\Repository\Ping\PingEntityRepository;
use Heph\Tests\Faker\Entity\Ping\PingEntityFaker;
use Heph\Tests\Functional\HephFunctionalTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use ReflectionException;

#[
    CoversClass(PingEntityRepository::class),
    CoversClass(PingEntity::class),
    CoversClass(Uid::class),
    CoversClass(UidType::class)
]
class PingEntityRepositoryTest extends HephFunctionalTestCase
{
    private PingEntityRepository $pingRepository;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $entityManager = self::getEntityManager();
        $entityManager->getConnection()->beginTransaction();

        /** @var PingEntityRepository $repository */
        $repository = self::getContainer()->get(PingEntityRepository::class);
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
     */
    public function testWeCanPersistAndFindPing(): void
    {
        $ping = PingEntityFaker::new();

        $this->persistAndFlush($ping);

        /** @var PingEntity|null $found */
        $found = $this->pingRepository->find($ping->id());

        self::assertNotNull($found, 'PingEntity non trouvé en base alors qu’on vient de le créer');
        self::assertSame(200, $found->status());
        self::assertSame('Le ping à réussi', $found->message());

    }
}
