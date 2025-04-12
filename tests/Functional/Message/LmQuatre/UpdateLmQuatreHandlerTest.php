<?php

declare(strict_types=1);

namespace Heph\Tests\Functional\Message\LmQuatre;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Heph\Entity\InfoDescriptionModel\Dto\InfoDescriptionModelCreateDto;
use Heph\Entity\InfoDescriptionModel\InfoDescriptionModel;
use Heph\Entity\LmQuatre\Dto\LmQuatreUpdateDto;
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
use Heph\Message\Command\LmQuatre\UpdateLmQuatreCommand;
use Heph\Message\Command\LmQuatre\UpdateLmQuatreHandler;
use Heph\Repository\LmQuatre\LmQuatreRepository;
use Heph\Tests\Faker\Dto\LmQuatre\LmQuatreUpdateDtoFaker;
use Heph\Tests\Faker\Entity\LmQuatre\LmQuatreFaker;
use Heph\Tests\Functional\HephFunctionalTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

#[
    CoversClass(LmQuatreRepository::class),
    CoversClass(LmQuatre::class),
    CoversClass(UpdateLmQuatreCommand::class),
    CoversClass(UpdateLmQuatreHandler::class),
    CoversClass(LmQuatreUpdateDto::class),
    CoversClass(DescriptionValueObject::class),
    CoversClass(LibelleValueObject::class),
    CoversClass(LibelleType::class),
    CoversClass(DescriptionType::class),
    CoversClass(InfoDescriptionModel::class),
    CoversClass(InfoDescriptionModelCreateDto::class),
    CoversClass(LmQuatreOwner::class),
    CoversClass(LmQuatreAdresse::class),
    CoversClass(LmQuatreEmail::class),
    CoversClass(LmQuatrePhoneNumber::class),
    CoversClass(LmQuatreOwnerType::class),
    CoversClass(LmQuatreAdresseType::class),
    CoversClass(LmQuatreEmailType::class),
    CoversClass(LmQuatrePhoneNumberType::class)
]
class UpdateLmQuatreHandlerTest extends HephFunctionalTestCase
{
    use InteractsWithMessenger;

    private EntityManagerInterface $entityManager;
    private LmQuatreRepository $repository;
    private UpdateLmQuatreHandler $handler;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        self::bootKernel();
        $this->entityManager = self::getEntityManager();
        $this->entityManager->getConnection()->beginTransaction();

        $this->repository = $this->entityManager->getRepository(LmQuatre::class);
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
    public function testUpdateLmQuatre(): void
    {
        $lmQuatre = LmQuatreFaker::new();
        $this->entityManager->persist($lmQuatre);
        $this->entityManager->flush();

        $firstLmQuatre = $this->repository->findOneBy([]);
        self::assertNotNull($firstLmQuatre, 'Entity non trouvée en bdd.');
        self::assertEquals('libelle test', $firstLmQuatre->infoDescriptionModel()->libelle());
        self::assertEquals('description test', $firstLmQuatre->infoDescriptionModel()->description());
        self::assertEquals('Math', $firstLmQuatre->owner());
        self::assertEquals('33 rue du test', $firstLmQuatre->adresse());
        self::assertEquals('test@exemple.com', $firstLmQuatre->email());
        self::assertEquals('123456789', $firstLmQuatre->phoneNumber());
        self::assertNotNull($firstLmQuatre->companyCreateDate());
        $bus = self::getContainer()->get('messenger.default_bus');
        $dto = LmQuatreUpdateDtoFaker::new();
        $command = new UpdateLmQuatreCommand($dto, (string) $firstLmQuatre->id());
        $bus->dispatch($command);
        $this->flush();

        $this->transport('async')->queue()->assertNotEmpty();
        $this->transport('async')->queue()->assertCount(1);
        $this->transport('async')->process(1);
        $this->transport('async')->queue()->assertCount(0);

        $updatedLmQuatre = $this->repository->findOneBy([]);
        self::assertNotNull($updatedLmQuatre, 'Entity non trouvée en bdd.');
        self::assertEquals($dto->owner, $updatedLmQuatre->owner()->value(), 'Le owner ne correspond pas au dto.');
    }
}
