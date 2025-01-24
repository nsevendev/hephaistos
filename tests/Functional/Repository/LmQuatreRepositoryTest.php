<?php

declare(strict_types=1);

namespace Heph\Tests\Functional\Repository;

use Doctrine\DBAL\Exception;
use Heph\Entity\LmQuatre\LmQuatre;
use Heph\Entity\Shared\Type\Uid;
use Heph\Infrastructure\Doctrine\Type\UidType;
use Heph\Repository\LmQuatre\LmQuatreRepository;
use Heph\Tests\Faker\Entity\LmQuatre\LmQuatreFaker;
use Heph\Tests\Functional\HephFunctionalTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use ReflectionException;

#[
    CoversClass(LmQuatreRepository::class),
    CoversClass(LmQuatre::class),
    CoversClass(Uid::class),
    CoversClass(UidType::class)
]
class LmQuatreRepositoryTest extends HephFunctionalTestCase
{
    private LmQuatreRepository $lmQuatreRepository;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->getConnection()->beginTransaction();

        /** @var LmQuatreRepository $repository */
        $repository = self::getContainer()->get(LmQuatreRepository::class);
        $this->lmQuatreRepository = $repository;
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
    public function testWeCanPersistAndFindLmQuatre(): void
    {
        $lmQuatre = LmQuatreFaker::new();

        $this->persistAndFlush($lmQuatre);

        /** @var LmQuatre|null $found */
        $found = $this->lmQuatreRepository->find($lmQuatre->id());

        self::assertNotNull($found, 'LmQuatre non trouvé en base alors qu’on vient de le créer');
        self::assertInstanceOf(LmQuatre::class, $found);
        self::assertSame('Math', $found->owner());
        self::assertSame('33 rue du test', $found->adresse());
        self::assertSame('test@exemple.com', $found->email());
        self::assertSame(123456789, $found->phoneNumber());
        self::assertNotNull($found->infoDescriptionModel());
    }

    /**
     * @throws ReflectionException
     */
    public function testWeCanUpdateLmQuatre(): void
    {
        $lmQuatre = LmQuatreFaker::new();

        $this->persistAndFlush($lmQuatre);

        $lmQuatre->setAdresse('34 rue nouvelle');
        $lmQuatre->setEmail('updated@example.com');
        $lmQuatre->setPhoneNumber(987654321);

        $this->persistAndFlush($lmQuatre);

        /** @var LmQuatre|null $found */
        $found = $this->lmQuatreRepository->find($lmQuatre->id());

        self::assertNotNull($found, 'LmQuatre non trouvé en base alors qu’on vient de le modifier');
        self::assertSame('34 rue nouvelle', $found->adresse());
        self::assertSame('updated@example.com', $found->email());
        self::assertSame(987654321, $found->phoneNumber());
    }
}
