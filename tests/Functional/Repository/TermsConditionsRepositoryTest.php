<?php

declare(strict_types=1);

namespace Heph\Tests\Functional\Repository;

use Doctrine\DBAL\Exception;
use Heph\Entity\InfoDescriptionModel\InfoDescriptionModel;
use Heph\Entity\Shared\ValueObject\DescriptionValueObject;
use Heph\Entity\Shared\ValueObject\LibelleValueObject;
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

    public function testPersitAndFlushWithRepository(): void
    {
        $termsConditions = TermsConditionsFaker::new();

        $this->termsConditionsRepository->save($termsConditions);

        /** @var TermsConditions|null $found */
        $found = $this->termsConditionsRepository->find($termsConditions->id());
        self::assertNotNull($found, 'TermsConditions non trouvé en base alors qu’on vient de le créer');
        self::assertSame('libelle test', $found->infoDescriptionModel()->libelle()->value());
        self::assertSame('description test', $found->infoDescriptionModel()->description()->value());
    }

    /**
     * @throws ReflectionException
     */
    public function testWeCanUpdateTermsConditions(): void
    {
        $termsConditions = TermsConditionsFaker::new();

        $this->persistAndFlush($termsConditions);

        $infoDescriptionModel = $termsConditions->infoDescriptionModel();
        $infoDescriptionModel->setLibelle(new LibelleValueObject('Nouveau libelle'));
        $infoDescriptionModel->setDescription(new DescriptionValueObject('Nouvelle description'));

        $this->persistAndFlush($termsConditions);

        /** @var TermsConditions|null $found */
        $found = $this->termsConditionsRepository->find($termsConditions->id());

        // Vérifications
        self::assertNotNull($found, 'TermsConditions non trouvé en base alors qu’on vient de le modifier');
        self::assertSame('Nouveau libelle', $found->infoDescriptionModel()->libelle()->value());
        self::assertSame('Nouvelle description', $found->infoDescriptionModel()->description()->value());
    }
}
