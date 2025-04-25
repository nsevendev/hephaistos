<?php

declare(strict_types=1);

namespace Heph\Tests\Functional\Message\TermsConditionsArticle;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Heph\Entity\InfoDescriptionModel\InfoDescriptionModel;
use Heph\Entity\Shared\ValueObject\DescriptionValueObject;
use Heph\Entity\Shared\ValueObject\LibelleValueObject;
use Heph\Entity\TermsConditions\TermsConditions;
use Heph\Entity\TermsConditionsArticle\Dto\TermsConditionsArticleCreateDto;
use Heph\Entity\TermsConditionsArticle\Dto\TermsConditionsArticleDto;
use Heph\Entity\TermsConditionsArticle\TermsConditionsArticle;
use Heph\Entity\TermsConditionsArticle\ValueObject\TermsConditionsArticleArticle;
use Heph\Entity\TermsConditionsArticle\ValueObject\TermsConditionsArticleTitle;
use Heph\Infrastructure\ApiResponse\Exception\Custom\AbstractApiResponseException;
use Heph\Infrastructure\ApiResponse\Exception\Custom\TermsConditionsArticle\TermsConditionsArticleInvalidArgumentException;
use Heph\Infrastructure\ApiResponse\Exception\Error\Error as ErrorError;
use Heph\Infrastructure\Doctrine\Types\Shared\DescriptionType;
use Heph\Infrastructure\Doctrine\Types\Shared\LibelleType;
use Heph\Infrastructure\Doctrine\Types\TermsConditionsArticle\TermsConditionsArticleArticleType;
use Heph\Infrastructure\Doctrine\Types\TermsConditionsArticle\TermsConditionsArticleTitleType;
use Heph\Infrastructure\Mercure\MercurePublish;
use Heph\Message\Command\TermsConditionsArticle\CreateTermsConditionsArticleCommand;
use Heph\Message\Command\TermsConditionsArticle\CreateTermsConditionsArticleHandler;
use Heph\Repository\TermsConditions\TermsConditionsRepository;
use Heph\Repository\TermsConditionsArticle\TermsConditionsArticleRepository;
use Heph\Tests\Faker\Dto\TermsConditionsArticle\TermsConditionsArticleCreateDtoFaker;
use Heph\Tests\Functional\HephFunctionalTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\Uid\Uuid;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

#[
    CoversClass(TermsConditionsArticleRepository::class),
    CoversClass(TermsConditionsArticle::class),
    CoversClass(CreateTermsConditionsArticleCommand::class),
    CoversClass(TermsConditionsArticleCreateDto::class),
    CoversClass(CreateTermsConditionsArticleHandler::class),
    CoversClass(MercurePublish::class),
    CoversClass(TermsConditionsArticleDto::class),
    CoversClass(TermsConditionsRepository::class),
    CoversClass(ErrorError::class),
    CoversClass(AbstractApiResponseException::class),
    CoversClass(TermsConditionsArticleInvalidArgumentException::class),
    CoversClass(TermsConditionsArticleArticle::class),
    CoversClass(TermsConditionsArticleTitle::class),
    CoversClass(DescriptionType::class),
    CoversClass(LibelleType::class),
    CoversClass(TermsConditionsArticleArticleType::class),
    CoversClass(TermsConditionsArticleTitleType::class),

    CoversClass(InfoDescriptionModel::class),
    CoversClass(DescriptionValueObject::class),
    CoversClass(LibelleValueObject::class),
    CoversClass(TermsConditions::class),
]
class CreateTermsConditionsArticleHandlerTest extends HephFunctionalTestCase
{
    use InteractsWithMessenger;

    private EntityManagerInterface $entityManager;
    private TermsConditionsArticleRepository $repository;
    private CreateTermsConditionsArticleHandler $handler;

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
    public function testHandlerProcessesMessage(): void
    {
        $bus = self::getContainer()->get('messenger.default_bus');

        $infoDescriptionModel = new InfoDescriptionModel(LibelleValueObject::fromValue('libelle test'), DescriptionValueObject::fromValue('description test'));
        $termsConditions = new TermsConditions($infoDescriptionModel);

        /** @var TermsConditionsRepository $termsConditionsRepository */
        $termsConditionsRepository = self::getContainer()->get(TermsConditionsRepository::class);
        $termsConditionsRepository->save($termsConditions);
        $this->flush();

        $dto = TermsConditionsArticleCreateDtoFaker::new((string) $termsConditions->id());
        $command = new CreateTermsConditionsArticleCommand($dto);
        $bus->dispatch($command);
        $this->flush();

        $this->transport('async')->queue()->assertNotEmpty();
        $messages = $this->transport('async')->queue()->messages();
        self::assertInstanceOf(CreateTermsConditionsArticleCommand::class, $messages[0]);
        $this->transport('async')->queue()->assertCount(1);
        $this->transport('async')->process(1);
        $this->transport('async')->queue()->assertCount(0);
    }

    public function testHandlerThrowsExceptionWhenTermsConditionsNotFound(): void
    {
        $handler = self::getContainer()->get(CreateTermsConditionsArticleHandler::class);
        $dto = TermsConditionsArticleCreateDtoFaker::new((string) Uuid::v7());
        $command = new CreateTermsConditionsArticleCommand($dto);

        $this->expectException(TermsConditionsArticleInvalidArgumentException::class);
        $handler($command);
    }
}
