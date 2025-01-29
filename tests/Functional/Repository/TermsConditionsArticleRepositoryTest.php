<?php

declare(strict_types=1);

namespace Heph\Tests\Functional\Repository;

use Doctrine\DBAL\Exception;
use Heph\Entity\TermsConditionsArticle\TermsConditionsArticle;
use Heph\Entity\TermsConditions\TermsConditions;
use Heph\Entity\InfoDescriptionModel\InfoDescriptionModel;
use Heph\Repository\TermsConditionsArticle\TermsConditionsArticleRepository;
use Heph\Tests\Faker\Entity\TermsConditionsArticle\TermsConditionsArticleFaker;
use Heph\Tests\Functional\HephFunctionalTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use ReflectionException;

#[
    CoversClass(TermsConditionsArticleRepository::class),
    CoversClass(TermsConditionsArticle::class),
    CoversClass(TermsConditions::class),
    CoversClass(InfoDescriptionModel::class),
]
class TermsConditionsArticleRepositoryTest extends HephFunctionalTestCase
{
    private TermsConditionsArticleRepository $termsConditionsArticleRepository;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->getConnection()->beginTransaction();

        /** @var TermsConditionsRepository $repository */
        $repository = self::getContainer()->get(TermsConditionsArticleRepository::class);
        $this->termsConditionsArticleRepository = $repository;
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
        $termsConditionsArticle = TermsConditionsArticleFaker::new();

        $this->persistAndFlush($termsConditionsArticle);

        /** @var TermsConditionsArticle|null $found */
        $found = $this->termsConditionsArticleRepository->find($termsConditionsArticle->id());

        // Vérifications
        self::assertNotNull($found, 'TermsConditionsArticle non trouvé en base alors qu’on vient de le créer');
        self::assertInstanceOf(TermsConditionsArticle::class, $found);
        self::assertNotNull($found->termsConditions());
        self::assertSame('titre test', $found->title());
        self::assertSame('article test', $found->article());
    }

    /**
     * @throws ReflectionException
     */
    public function testWeCanUpdateTermsConditions(): void
    {
        $termsConditionsArticle = TermsConditionsArticleFaker::new();

        $this->persistAndFlush($termsConditionsArticle);

        $termsConditionsArticle->setTitle('new title');
        $termsConditionsArticle->setArticle('new article content');

        $this->persistAndFlush($termsConditionsArticle);

        /** @var TermsConditionsArticle|null $found */
        $found = $this->termsConditionsArticleRepository->find($termsConditionsArticle->id());

        // Vérifications
        self::assertNotNull($found, 'TermsConditionsArticle non trouvé en base alors qu’on vient de le modifier');
        self::assertSame('new title', $found->title());
        self::assertSame('new article content', $found->article());
    }
}
