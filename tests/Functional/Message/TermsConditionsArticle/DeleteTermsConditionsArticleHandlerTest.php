<?php

declare(strict_types=1);

namespace Functional\Message\TermsConditionsArticle;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Heph\Entity\InfoDescriptionModel\InfoDescriptionModel;
use Heph\Entity\Shared\ValueObject\DescriptionValueObject;
use Heph\Entity\Shared\ValueObject\LibelleValueObject;
use Heph\Entity\TermsConditions\TermsConditions;
use Heph\Entity\TermsConditionsArticle\Dto\TermsConditionsArticleDeleteDto;
use Heph\Entity\TermsConditionsArticle\TermsConditionsArticle;
use Heph\Entity\TermsConditionsArticle\ValueObject\TermsConditionsArticleArticle;
use Heph\Entity\TermsConditionsArticle\ValueObject\TermsConditionsArticleTitle;
use Heph\Infrastructure\ApiResponse\Exception\Custom\AbstractApiResponseException;
use Heph\Infrastructure\ApiResponse\Exception\Custom\TermsConditionsArticle\TermsConditionsArticleBadRequestException;
use Heph\Infrastructure\ApiResponse\Exception\Error\Error;
use Heph\Infrastructure\Doctrine\Types\Shared\DescriptionType;
use Heph\Infrastructure\Doctrine\Types\Shared\LibelleType;
use Heph\Infrastructure\Doctrine\Types\TermsConditionsArticle\TermsConditionsArticleArticleType;
use Heph\Infrastructure\Doctrine\Types\TermsConditionsArticle\TermsConditionsArticleTitleType;
use Heph\Infrastructure\Mercure\MercurePublish;
use Heph\Message\Command\TermsConditionsArticle\DeleteTermsConditionsArticleCommand;
use Heph\Message\Command\TermsConditionsArticle\DeleteTermsConditionsArticleHandler;
use Heph\Repository\TermsConditionsArticle\TermsConditionsArticleRepository;
use Heph\Tests\Faker\Entity\TermsConditionsArticle\TermsConditionsArticleFaker;
use Heph\Tests\Functional\HephFunctionalTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Uid\Uuid;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

#[
    CoversClass(TermsConditionsArticleRepository::class),
    CoversClass(TermsConditionsArticle::class),
    CoversClass(DeleteTermsConditionsArticleCommand::class),
    CoversClass(DeleteTermsConditionsArticleHandler::class),
    CoversClass(MercurePublish::class),
    CoversClass(TermsConditionsArticleDeleteDto::class),
    CoversClass(AbstractApiResponseException::class),
    CoversClass(TermsConditionsArticleBadRequestException::class),
    CoversClass(Error::class),
    CoversClass(TermsConditionsArticleTitle::class),
    CoversClass(TermsConditionsArticleArticle::class),
    CoversClass(TermsConditionsArticleTitleType::class),
    CoversClass(TermsConditionsArticleArticleType::class),
    CoversClass(InfoDescriptionModel::class),
    CoversClass(DescriptionValueObject::class),
    CoversClass(LibelleValueObject::class),
    CoversClass(TermsConditions::class),
    CoversClass(DescriptionType::class),
    CoversClass(LibelleType::class),
]
class DeleteTermsConditionsArticleHandlerTest extends HephFunctionalTestCase
{
    use InteractsWithMessenger;

    private EntityManagerInterface $entityManager;

    private TermsConditionsArticleRepository $repository;

    private DeleteTermsConditionsArticleHandler $handler;

    // private MercurePublish $mercurePublish;

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

    public function testDoctrineConfiguration(): void
    {
        $connection = self::getEntityManager()->getConnection();
        self::assertTrue($connection->isConnected(), 'La connexion à la base de données est inactive');
    }

    public function testDeleteTermsConditionsArticle(): void
    {
        $termsConditionsArticle = TermsConditionsArticleFaker::new();

        $this->entityManager->persist($termsConditionsArticle);
        $this->entityManager->flush();

        $bus = self::getContainer()->get('messenger.default_bus');
        $command = new DeleteTermsConditionsArticleCommand($termsConditionsArticle->id()->toString());
        $bus->dispatch($command);
        $this->flush();

        $this->transport('async')->queue()->assertNotEmpty();
        $this->transport('async')->queue()->assertCount(1);
        $this->transport('async')->process(1);
        $this->transport('async')->queue()->assertCount(0);
    }

    public function testDeleteTermsConditionsArticleNotExist(): void
    {
        $this->expectException(HandlerFailedException::class);
        $id = Uuid::v7()->toString();
        $this->transport('othersync')->send(new DeleteTermsConditionsArticleCommand($id));
        $this->transport('othersync')->process(1);
        $this->transport('othersync')->catchExceptions();
    }
}
