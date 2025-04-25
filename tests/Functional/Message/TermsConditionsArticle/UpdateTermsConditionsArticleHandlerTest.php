<?php

declare(strict_types=1);

namespace Heph\Tests\Functional\Message\TermsConditionsArticle;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Heph\Entity\InfoDescriptionModel\Dto\InfoDescriptionModelCreateDto;
use Heph\Entity\InfoDescriptionModel\InfoDescriptionModel;
use Heph\Entity\Shared\ValueObject\DescriptionValueObject;
use Heph\Entity\Shared\ValueObject\LibelleValueObject;
use Heph\Entity\TermsConditions\TermsConditions;
use Heph\Entity\TermsConditionsArticle\Dto\TermsConditionsArticleUpdateDto;
use Heph\Entity\TermsConditionsArticle\TermsConditionsArticle;
use Heph\Entity\TermsConditionsArticle\ValueObject\TermsConditionsArticleArticle;
use Heph\Entity\TermsConditionsArticle\ValueObject\TermsConditionsArticleTitle;
use Heph\Infrastructure\Doctrine\Types\Shared\DescriptionType;
use Heph\Infrastructure\Doctrine\Types\Shared\LibelleType;
use Heph\Infrastructure\Doctrine\Types\TermsConditionsArticle\TermsConditionsArticleArticleType;
use Heph\Infrastructure\Doctrine\Types\TermsConditionsArticle\TermsConditionsArticleTitleType;
use Heph\Message\Command\TermsConditionsArticle\UpdateTermsConditionsArticleCommand;
use Heph\Message\Command\TermsConditionsArticle\UpdateTermsConditionsArticleHandler;
use Heph\Repository\TermsConditionsArticle\TermsConditionsArticleRepository;
use Heph\Tests\Faker\Dto\TermsConditionsArticle\TermsConditionsArticleUpdateDtoFaker;
use Heph\Tests\Faker\Entity\TermsConditionsArticle\TermsConditionsArticleFaker;
use Heph\Tests\Functional\HephFunctionalTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

#[
    CoversClass(TermsConditionsArticleRepository::class),
    CoversClass(TermsConditionsArticle::class),
    CoversClass(UpdateTermsConditionsArticleCommand::class),
    CoversClass(UpdateTermsConditionsArticleHandler::class),
    CoversClass(TermsConditionsArticleUpdateDto::class),
    CoversClass(DescriptionValueObject::class),
    CoversClass(LibelleValueObject::class),
    CoversClass(LibelleType::class),
    CoversClass(DescriptionType::class),
    CoversClass(InfoDescriptionModel::class),
    CoversClass(InfoDescriptionModelCreateDto::class),
    CoversClass(TermsConditionsArticleArticle::class),
    CoversClass(TermsConditionsArticleTitleType::class),
    CoversClass(TermsConditionsArticleArticleType::class),
    CoversClass(TermsConditionsArticleTitle::class),
    CoversClass(TermsConditions::class)
]
class UpdateTermsConditionsArticleHandlerTest extends HephFunctionalTestCase
{
    use InteractsWithMessenger;

    private EntityManagerInterface $entityManager;
    private TermsConditionsArticleRepository $repository;
    private UpdateTermsConditionsArticleHandler $handler;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        self::bootKernel();
        $this->entityManager = self::getEntityManager();
        $this->entityManager->getConnection()->beginTransaction();

        $this->repository = $this->entityManager->getRepository(TermsConditionsArticle::class);
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
    public function testUpdateTermsConditionsArticle(): void
    {
        $termsConditionsArticle = TermsConditionsArticleFaker::new();
        $this->entityManager->persist($termsConditionsArticle);
        $this->entityManager->flush();

        $firstTermsConditionsArticle = $this->repository->findOneBy([]);
        self::assertNotNull($firstTermsConditionsArticle, 'Entity non trouvée en bdd.');
        self::assertEquals('titre test', $firstTermsConditionsArticle->title());
        self::assertEquals('article test', $firstTermsConditionsArticle->article());

        $bus = self::getContainer()->get('messenger.default_bus');
        $dto = TermsConditionsArticleUpdateDtoFaker::new();
        $command = new UpdateTermsConditionsArticleCommand($dto, (string) $firstTermsConditionsArticle->id());
        $bus->dispatch($command);
        $this->flush();

        $this->transport('async')->queue()->assertNotEmpty();
        $this->transport('async')->queue()->assertCount(1);
        $this->transport('async')->process(1);
        $this->transport('async')->queue()->assertCount(0);

        $updatedTermsConditionsArticle = $this->repository->findOneBy([]);
        self::assertNotNull($updatedTermsConditionsArticle, 'Entity non trouvée en bdd.');
    }
}
