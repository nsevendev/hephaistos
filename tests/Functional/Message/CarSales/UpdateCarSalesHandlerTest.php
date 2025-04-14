<?php

declare(strict_types=1);

namespace Heph\Tests\Functional\Message\CarSales;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Heph\Entity\CarSales\CarSales;
use Heph\Entity\CarSales\Dto\CarSalesUpdateDto;
use Heph\Entity\InfoDescriptionModel\Dto\InfoDescriptionModelCreateDto;
use Heph\Entity\InfoDescriptionModel\InfoDescriptionModel;
use Heph\Entity\Shared\ValueObject\DescriptionValueObject;
use Heph\Entity\Shared\ValueObject\LibelleValueObject;
use Heph\Infrastructure\Doctrine\Types\Shared\DescriptionType;
use Heph\Infrastructure\Doctrine\Types\Shared\LibelleType;
use Heph\Message\Command\CarSales\UpdateCarSalesCommand;
use Heph\Message\Command\CarSales\UpdateCarSalesHandler;
use Heph\Repository\CarSales\CarSalesRepository;
use Heph\Tests\Faker\Dto\CarSales\CarSalesUpdateDtoFaker;
use Heph\Tests\Faker\Entity\CarSales\CarSalesFaker;
use Heph\Tests\Functional\HephFunctionalTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

#[
    CoversClass(CarSalesRepository::class),
    CoversClass(CarSales::class),
    CoversClass(UpdateCarSalesCommand::class),
    CoversClass(UpdateCarSalesHandler::class),
    CoversClass(CarSalesUpdateDto::class),
    CoversClass(DescriptionValueObject::class),
    CoversClass(LibelleValueObject::class),
    CoversClass(LibelleType::class),
    CoversClass(DescriptionType::class),
    CoversClass(InfoDescriptionModel::class),
    CoversClass(InfoDescriptionModelCreateDto::class)
]
class UpdateCarSalesHandlerTest extends HephFunctionalTestCase
{
    use InteractsWithMessenger;

    private EntityManagerInterface $entityManager;
    private CarSalesRepository $repository;
    private UpdateCarSalesHandler $handler;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        self::bootKernel();
        $this->entityManager = self::getEntityManager();
        $this->entityManager->getConnection()->beginTransaction();

        $this->repository = $this->entityManager->getRepository(CarSales::class);
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
    public function testUpdateCarSales(): void
    {
        $carSales = CarSalesFaker::new();
        $this->entityManager->persist($carSales);
        $this->entityManager->flush();

        $firstCarSales = $this->repository->findOneBy([]);
        self::assertNotNull($firstCarSales, 'Entity non trouvée en bdd.');
        self::assertEquals('libelle test', $firstCarSales->infoDescriptionModel()->libelle());
        self::assertEquals('description test', $firstCarSales->infoDescriptionModel()->description());

        $bus = self::getContainer()->get('messenger.default_bus');
        $dto = CarSalesUpdateDtoFaker::new();
        $command = new UpdateCarSalesCommand($dto, (string) $firstCarSales->id());
        $bus->dispatch($command);
        $this->flush();

        $this->transport('async')->queue()->assertNotEmpty();
        $this->transport('async')->queue()->assertCount(1);
        $this->transport('async')->process(1);
        $this->transport('async')->queue()->assertCount(0);

        $updatedCarSales = $this->repository->findOneBy([]);
        self::assertNotNull($updatedCarSales, 'Entity non trouvée en bdd.');
    }
}
