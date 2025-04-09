<?php

declare(strict_types=1);

namespace Heph\Tests\Functional\Repository;

use Doctrine\DBAL\Exception;
use Heph\Entity\InfoDescriptionModel\InfoDescriptionModel;
use Heph\Entity\LmQuatre\LmQuatre;
use Heph\Entity\LmQuatre\ValueObject\LmQuatreAdresse;
use Heph\Entity\LmQuatre\ValueObject\LmQuatreEmail;
use Heph\Entity\LmQuatre\ValueObject\LmQuatreOwner;
use Heph\Entity\LmQuatre\ValueObject\LmQuatrePhoneNumber;
use Heph\Entity\Shared\ValueObject\DescriptionValueObject;
use Heph\Entity\Shared\ValueObject\LibelleValueObject;
use Heph\Infrastructure\Doctrine\Types\LmQuatre\LmQuatreAdresseType;
use Heph\Infrastructure\Doctrine\Types\LmQuatre\LmQuatreEmailType;
use Heph\Infrastructure\Doctrine\Types\LmQuatre\LmQuatreOwnerType;
use Heph\Infrastructure\Doctrine\Types\LmQuatre\LmQuatrePhoneNumberType;
use Heph\Infrastructure\Doctrine\Types\Shared\DescriptionType;
use Heph\Infrastructure\Doctrine\Types\Shared\LibelleType;
use Heph\Repository\LmQuatre\LmQuatreRepository;
use Heph\Tests\Faker\Entity\LmQuatre\LmQuatreFaker;
use Heph\Tests\Functional\HephFunctionalTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use ReflectionException;

#[
    CoversClass(LmQuatreRepository::class),
    CoversClass(LmQuatre::class),
    CoversClass(InfoDescriptionModel::class),
    CoversClass(LibelleValueObject::class),
    CoversClass(DescriptionValueObject::class),
    CoversClass(LibelleType::class),
    CoversClass(DescriptionType::class),
    CoversClass(LmQuatreAdresse::class),
    CoversClass(LmQuatreEmail::class),
    CoversClass(LmQuatrePhoneNumber::class),
    CoversClass(LmQuatreOwner::class),
    CoversClass(LmQuatreAdresseType::class),
    CoversClass(LmQuatreEmailType::class),
    CoversClass(LmQuatreOwnerType::class),
    CoversClass(LmQuatrePhoneNumberType::class),
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
        self::assertSame('Math', $found->owner()->value());
        self::assertSame('33 rue du test', $found->adresse()->value());
        self::assertSame('test@exemple.com', $found->email()->value());
        self::assertSame('123456789', $found->phoneNumber()->value());
        self::assertNotNull($found->infoDescriptionModel());
    }

    /**
     * @throws ReflectionException
     */
    public function testWeCanUpdateLmQuatre(): void
    {
        $lmQuatre = LmQuatreFaker::new();

        $this->persistAndFlush($lmQuatre);

        $lmQuatre->setAdresse(LmQuatreAdresse::fromValue('34 rue nouvelle'));
        $lmQuatre->setEmail(LmQuatreEmail::fromValue('updated@example.com'));
        $lmQuatre->setPhoneNumber(LmQuatrePhoneNumber::fromValue('987654321'));

        $this->persistAndFlush($lmQuatre);

        /** @var LmQuatre|null $found */
        $found = $this->lmQuatreRepository->find($lmQuatre->id());

        self::assertNotNull($found, 'LmQuatre non trouvé en base alors qu’on vient de le modifier');
        self::assertSame('34 rue nouvelle', $found->adresse()->value());
        self::assertSame('updated@example.com', $found->email()->value());
        self::assertSame('987654321', $found->phoneNumber()->value());
    }
}
