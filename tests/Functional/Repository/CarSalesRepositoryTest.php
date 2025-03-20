<?php

declare(strict_types=1);

namespace Heph\Tests\Functional\Repository;

use Doctrine\DBAL\Exception;
use Heph\Entity\CarSales\CarSales;
use Heph\Entity\InfoDescriptionModel\InfoDescriptionModel;
use Heph\Entity\Shared\ValueObject\DescriptionValueObject;
use Heph\Entity\Shared\ValueObject\LibelleValueObject;
use Heph\Repository\CarSales\CarSalesRepository;
use Heph\Tests\Faker\Entity\CarSales\CarSalesFaker;
use Heph\Tests\Functional\HephFunctionalTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use ReflectionException;

#[
    CoversClass(CarSalesRepository::class),
    CoversClass(CarSales::class),
    CoversClass(InfoDescriptionModel::class),
]
class CarSalesRepositoryTest extends HephFunctionalTestCase
{
    private CarSalesRepository $carSalesRepository;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->getConnection()->beginTransaction();

        /** @var CarSalesRepository $repository */
        $repository = self::getContainer()->get(CarSalesRepository::class);
        $this->carSalesRepository = $repository;
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
    public function testWeCanPersistAndFindCarSales(): void
    {
        $carSales = CarSalesFaker::new();

        $this->persistAndFlush($carSales);

        /** @var CarSales|null $found */
        $found = $this->carSalesRepository->find($carSales->id());

        self::assertNotNull($found, 'CarSales non trouvé en base alors qu’on vient de le créer');
        self::assertInstanceOf(CarSales::class, $found);
        self::assertNotNull($found->infoDescriptionModel());
    }

    /**
     * @throws ReflectionException
     */
    public function testWeCanUpdateCarSales(): void
    {
        $carSales = CarSalesFaker::new();

        $this->persistAndFlush($carSales);

        $infoDescriptionModel = $carSales->infoDescriptionModel();
        $infoDescriptionModel->setLibelle(new LibelleValueObject('Nouveau libellé'));
        $infoDescriptionModel->setDescription(new DescriptionValueObject('Nouvelle description'));

        $this->persistAndFlush($carSales);

        /** @var CarSales|null $found */
        $found = $this->carSalesRepository->find($carSales->id());

        self::assertNotNull($found, 'CarSales non trouvé en base alors qu’on vient de le modifier');
        self::assertSame('Nouveau libellé', $found->infoDescriptionModel()->libelle()->value());
        self::assertSame('Nouvelle description', $found->infoDescriptionModel()->description()->value());
    }
}
