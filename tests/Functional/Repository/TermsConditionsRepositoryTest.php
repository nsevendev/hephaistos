<?php

declare(strict_types=1);

namespace Heph\Tests\Functional\Repository;

use Doctrine\DBAL\Exception;
use Heph\Entity\InfoDescriptionModel\InfoDescriptionModel;
use Heph\Entity\TermsConditions\TermsConditions;
use Heph\Repository\TermsConditions\TermsConditionsRepository;
use Heph\Tests\Faker\Entity\TermsConditions\TermsConditionsFaker;
use Heph\Tests\Functional\HephFunctionalTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use ReflectionException;

#[
    CoversClass(TermsConditionsRepository::class),
    CoversClass(TermsConditions::class),
    CoversClass(InfoDescriptionModel::class),
]
class TermsConditionsRepositoryTest extends HephFunctionalTestCase
{
    private TermsConditionsRepository $termsConditionsRepository;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->getConnection()->beginTransaction();

        /** @var TermsConditionsRepository $repository */
        $repository = self::getContainer()->get(TermsConditionsRepository::class);
        $this->termsConditionsRepository = $repository;
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
    public function testWeCanPersistAndFindTermsConditions(): void
    {
        $termsConditions = TermsConditionsFaker::new();

        $this->persistAndFlush($termsConditions);

        /** @var TermsConditions|null $found */
        $found = $this->termsConditionsRepository->find($termsConditions->id());

        // Vérifications
        self::assertNotNull($found, 'TermsConditions non trouvé en base alors qu’on vient de le créer');
        self::assertInstanceOf(TermsConditions::class, $found);
        self::assertNotNull($found->infoDescriptionModel());
    }

    public function testWeCanSaveTermsConditions(): void
    {
        $termsConditions = TermsConditionsFaker::new();

        $this->termsConditionsRepository->save($termsConditions);

        $found = $this->termsConditionsRepository->find($termsConditions->id());
        self::assertNotNull($found, 'TermsConditions non trouvé en base alors qu’on vient de le créer');
        self::assertSame('libellé test', $found->infoDescriptionModel()->libelle());
        self::assertSame('description test', $found->infoDescriptionModel()->description());
    }

    /**
     * @throws ReflectionException
     */
    public function testWeCanUpdateTermsConditions(): void
    {
        $termsConditions = TermsConditionsFaker::new();

        $this->persistAndFlush($termsConditions);

        $infoDescriptionModel = $termsConditions->infoDescriptionModel();
        $infoDescriptionModel->setLibelle('Nouveau libellé');
        $infoDescriptionModel->setDescription('Nouvelle description');

        $this->persistAndFlush($termsConditions);

        /** @var TermsConditions|null $found */
        $found = $this->termsConditionsRepository->find($termsConditions->id());

        // Vérifications
        self::assertNotNull($found, 'TermsConditions non trouvé en base alors qu’on vient de le modifier');
        self::assertSame('Nouveau libellé', $found->infoDescriptionModel()->libelle());
        self::assertSame('Nouvelle description', $found->infoDescriptionModel()->description());
    }
}
