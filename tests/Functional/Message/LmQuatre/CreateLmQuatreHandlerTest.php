<?php

declare(strict_types=1);

namespace Heph\Tests\Functional\Message\LmQuatre;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Heph\Entity\InfoDescriptionModel\Dto\InfoDescriptionModelCreateDto;
use Heph\Entity\InfoDescriptionModel\Dto\InfoDescriptionModelDto;
use Heph\Entity\InfoDescriptionModel\InfoDescriptionModel;
use Heph\Entity\LmQuatre\Dto\LmQuatreCreateDto;
use Heph\Entity\LmQuatre\Dto\LmQuatreDto;
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
use Heph\Infrastructure\Mercure\MercurePublish;
use Heph\Message\Command\LmQuatre\CreateLmQuatreCommand;
use Heph\Message\Command\LmQuatre\CreateLmQuatreHandler;
use Heph\Repository\InfoDescriptionModel\InfoDescriptionModelRepository;
use Heph\Repository\LmQuatre\LmQuatreRepository;
use Heph\Tests\Faker\Dto\LmQuatre\LmQuatreCreateDtoFaker;
use Heph\Tests\Functional\HephFunctionalTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

#[
    CoversClass(LmQuatreRepository::class),
    CoversClass(LmQuatre::class),
    CoversClass(CreateLmQuatreCommand::class),
    CoversClass(LmQuatreCreateDto::class),
    CoversClass(CreateLmQuatreHandler::class),
    CoversClass(MercurePublish::class),
    CoversClass(LmQuatreDto::class),
    CoversClass(InfoDescriptionModelCreateDto::class),
    CoversClass(InfoDescriptionModel::class),
    CoversClass(DescriptionValueObject::class),
    CoversClass(LibelleValueObject::class),
    CoversClass(InfoDescriptionModelRepository::class),
    CoversClass(LibelleType::class),
    CoversClass(DescriptionType::class),
    CoversClass(InfoDescriptionModelDto::class),
    CoversClass(LmQuatreOwner::class),
    CoversClass(LmQuatreAdresse::class),
    CoversClass(LmQuatreEmail::class),
    CoversClass(LmQuatrePhoneNumber::class),
    CoversClass(LmQuatreOwnerType::class),
    CoversClass(LmQuatreAdresseType::class),
    CoversClass(LmQuatreEmailType::class),
    CoversClass(LmQuatrePhoneNumberType::class),
]
class CreateLmQuatreHandlerTest extends HephFunctionalTestCase
{
    use InteractsWithMessenger;

    private EntityManagerInterface $entityManager;
    private LmQuatreRepository $repository;
    private CreateLmQuatreHandler $handler;

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
    public function testHandlerProcessesMessage(): void
    {
        $bus = self::getContainer()->get('messenger.default_bus');
        $dto = LmQuatreCreateDtoFaker::new();
        $infoDto = InfoDescriptionModelCreateDto::new('libelle test', 'description test');
        $command = new CreateLmQuatreCommand($dto, $infoDto);
        $bus->dispatch($command);
        $this->flush();

        $this->transport('async')->queue()->assertNotEmpty();
        $m = $this->transport('async')->queue()->messages();
        self::assertInstanceOf(CreateLmQuatreCommand::class, $m[0]);
        $this->transport('async')->queue()->assertCount(1);
        $this->transport('async')->process(1);
        $this->transport('async')->queue()->assertCount(0);
    }
}
