<?php

declare(strict_types=1);

namespace Heph\Tests\Functional\Message\CarSales;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Heph\Entity\CarSales\CarSales;
use Heph\Entity\CarSales\Dto\CarSalesCreateDto;
use Heph\Entity\CarSales\Dto\CarSalesDto;
use Heph\Entity\InfoDescriptionModel\Dto\InfoDescriptionModelCreateDto;
use Heph\Entity\InfoDescriptionModel\Dto\InfoDescriptionModelDto;
use Heph\Entity\InfoDescriptionModel\InfoDescriptionModel;
use Heph\Entity\Shared\ValueObject\DescriptionValueObject;
use Heph\Entity\Shared\ValueObject\LibelleValueObject;
use Heph\Infrastructure\Doctrine\Types\Shared\DescriptionType;
use Heph\Infrastructure\Doctrine\Types\Shared\LibelleType;
use Heph\Infrastructure\Mercure\MercurePublish;
use Heph\Message\Command\CarSales\CreateCarSalesCommand;
use Heph\Message\Command\CarSales\CreateCarSalesHandler;
use Heph\Repository\CarSales\CarSalesRepository;
use Heph\Repository\InfoDescriptionModel\InfoDescriptionModelRepository;
use Heph\Tests\Faker\Dto\CarSales\CarSalesCreateDtoFaker;
use Heph\Tests\Functional\HephFunctionalTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

#[
    CoversClass(CarSalesRepository::class),
    CoversClass(CarSales::class),
    CoversClass(CreateCarSalesCommand::class),
    CoversClass(CarSalesCreateDto::class),
    CoversClass(CreateCarSalesHandler::class),
    CoversClass(MercurePublish::class),
    CoversClass(CarSalesDto::class),
    CoversClass(InfoDescriptionModelCreateDto::class),
    CoversClass(InfoDescriptionModel::class),
    CoversClass(DescriptionValueObject::class),
    CoversClass(LibelleValueObject::class),
    CoversClass(InfoDescriptionModelRepository::class),
    CoversClass(LibelleType::class),
    CoversClass(DescriptionType::class),
    CoversClass(InfoDescriptionModelDto::class)
]
class CreateCarSalesHandlerTest extends HephFunctionalTestCase
{
    use InteractsWithMessenger;

    private EntityManagerInterface $entityManager;
    private CarSalesRepository $repository;
    private CreateCarSalesHandler $handler;

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
    public function testHandlerProcessesMessage(): void
    {
        $bus = self::getContainer()->get('messenger.default_bus');
        $dto = CarSalesCreateDtoFaker::new();
        $infoDto = InfoDescriptionModelCreateDto::new('libelle test', 'description test');
        $command = new CreateCarSalesCommand($dto, $infoDto);
        $bus->dispatch($command);
        $this->flush();

        $this->transport('async')->queue()->assertNotEmpty();
        $m = $this->transport('async')->queue()->messages();
        self::assertInstanceOf(CreateCarSalesCommand::class, $m[0]);
        $this->transport('async')->queue()->assertCount(1);
        $this->transport('async')->process(1);
        $this->transport('async')->queue()->assertCount(0);
    }
}
