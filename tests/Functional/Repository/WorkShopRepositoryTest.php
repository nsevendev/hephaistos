<?php

declare(strict_types=1);

namespace Heph\Tests\Functional\Repository;

use Doctrine\DBAL\Exception;
use Heph\Entity\InfoDescriptionModel\InfoDescriptionModel;
use Heph\Entity\WorkShop\WorkShop;
use Heph\Repository\WorkShop\WorkShopRepository;
use Heph\Tests\Faker\Entity\WorkShop\WorkShopFaker;
use Heph\Tests\Functional\HephFunctionalTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use ReflectionException;

#[
    CoversClass(WorkShopRepository::class),
    CoversClass(WorkShop::class),
    CoversClass(InfoDescriptionModel::class),
]
class WorkShopRepositoryTest extends HephFunctionalTestCase
{
    private WorkShopRepository $workShopRepository;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->getConnection()->beginTransaction();

        /** @var WorkShopRepository $repository */
        $repository = self::getContainer()->get(WorkShopRepository::class);
        $this->workShopRepository = $repository;
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
    public function testWeCanPersistAndFindWorkShop(): void
    {
        $workShop = WorkShopFaker::new();

        $this->persistAndFlush($workShop);

        /** @var WorkShop|null $found */
        $found = $this->workShopRepository->find($workShop->id());

        // Vérifications
        self::assertNotNull($found, 'WorkShop non trouvé en base alors qu’on vient de le créer');
        self::assertInstanceOf(WorkShop::class, $found);
        self::assertNotNull($found->infoDescriptionModel());
    }

    /**
     * @throws ReflectionException
     */
    public function testWeCanUpdateWorkShop(): void
    {
        $workShop = WorkShopFaker::new();

        $this->persistAndFlush($workShop);

        $infoDescriptionModel = $workShop->infoDescriptionModel();
        $infoDescriptionModel->setLibelle('Nouveau libellé');
        $infoDescriptionModel->setDescription('Nouvelle description');

        $this->persistAndFlush($workShop);

        /** @var WorkShop|null $found */
        $found = $this->workShopRepository->find($workShop->id());

        // Vérifications
        self::assertNotNull($found, 'WorkShop non trouvé en base alors qu’on vient de le modifier');
        self::assertSame('Nouveau libellé', $found->infoDescriptionModel()->libelle());
        self::assertSame('Nouvelle description', $found->infoDescriptionModel()->description());
    }
}
