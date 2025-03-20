<?php

declare(strict_types=1);

namespace Heph\Tests\Functional\Repository;

use Doctrine\DBAL\Exception;
use Heph\Entity\InfoDescriptionModel\InfoDescriptionModel;
use Heph\Entity\Shared\ValueObject\DescriptionValueObject;
use Heph\Entity\Shared\ValueObject\LibelleValueObject;
use Heph\Repository\InfoDescriptionModel\InfoDescriptionModelRepository;
use Heph\Tests\Faker\Entity\InfoDescriptionModel\InfoDescriptionModelFaker;
use Heph\Tests\Functional\HephFunctionalTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use ReflectionException;

#[
    CoversClass(InfoDescriptionModelRepository::class),
    CoversClass(InfoDescriptionModel::class),
]
class InfoDescriptionModelRepositoryTest extends HephFunctionalTestCase
{
    private InfoDescriptionModelRepository $infoDescriptionModelRepository;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->getConnection()->beginTransaction();

        /** @var InfoDescriptionModelRepository $repository */
        $repository = self::getContainer()->get(InfoDescriptionModelRepository::class);
        $this->infoDescriptionModelRepository = $repository;
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
    public function testWeCanPersistAndFindInfoDescriptionModel(): void
    {
        $infoDescriptionModel = InfoDescriptionModelFaker::new();

        $this->persistAndFlush($infoDescriptionModel);

        /** @var InfoDescriptionModel|null $found */
        $found = $this->infoDescriptionModelRepository->find($infoDescriptionModel->id());

        self::assertNotNull($found, 'InfoDescriptionModel non trouvé en base alors qu’on vient de le créer');
        self::assertInstanceOf(InfoDescriptionModel::class, $found);
        self::assertSame('libelle test', $found->libelle()->value());
        self::assertSame('description test', $found->description()->value());
    }

    /**
     * @throws ReflectionException
     */
    public function testWeCanUpdateInfoDescriptionModel(): void
    {
        $infoDescriptionModel = InfoDescriptionModelFaker::new();

        $this->persistAndFlush($infoDescriptionModel);

        $infoDescriptionModel->setLibelle(new LibelleValueObject('Nouveau libelle'));
        $infoDescriptionModel->setDescription(new DescriptionValueObject('Nouvelle description'));

        $this->persistAndFlush($infoDescriptionModel);

        /** @var InfoDescriptionModel|null $found */
        $found = $this->infoDescriptionModelRepository->find($infoDescriptionModel->id());

        self::assertNotNull($found, 'InfoDescriptionModel non trouvé en base alors qu’on vient de le modifier');
        self::assertSame('Nouveau libelle', $found->libelle()->value());
        self::assertSame('Nouvelle description', $found->description()->value());
    }
}
