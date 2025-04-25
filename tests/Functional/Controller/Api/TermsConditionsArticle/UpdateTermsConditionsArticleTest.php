<?php

declare(strict_types=1);

namespace Heph\Tests\Functional\Controller\Api\TermsConditionsArticle;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Heph\Controller\Api\TermsConditionsArticle\UpdateTermsConditionsArticle;
use Heph\Entity\InfoDescriptionModel\InfoDescriptionModel;
use Heph\Entity\Shared\ValueObject\DescriptionValueObject;
use Heph\Entity\Shared\ValueObject\LibelleValueObject;
use Heph\Entity\TermsConditions\TermsConditions;
use Heph\Entity\TermsConditionsArticle\Dto\TermsConditionsArticleUpdateDto;
use Heph\Entity\TermsConditionsArticle\TermsConditionsArticle;
use Heph\Entity\TermsConditionsArticle\ValueObject\TermsConditionsArticleArticle;
use Heph\Entity\TermsConditionsArticle\ValueObject\TermsConditionsArticleTitle;
use Heph\Infrastructure\ApiResponse\ApiResponse;
use Heph\Infrastructure\ApiResponse\ApiResponseFactory;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseData;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseLink;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseMessage;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseMeta;
use Heph\Infrastructure\ApiResponse\Exception\Error\ListError;
use Heph\Infrastructure\Doctrine\Types\Shared\DescriptionType;
use Heph\Infrastructure\Doctrine\Types\Shared\LibelleType;
use Heph\Infrastructure\Doctrine\Types\TermsConditionsArticle\TermsConditionsArticleArticleType;
use Heph\Infrastructure\Doctrine\Types\TermsConditionsArticle\TermsConditionsArticleTitleType;
use Heph\Infrastructure\Serializer\HephSerializer;
use Heph\Infrastructure\Serializer\Normalizer\ValueObjectNormalizer;
use Heph\Message\Command\TermsConditionsArticle\UpdateTermsConditionsArticleCommand;
use Heph\Repository\TermsConditionsArticle\TermsConditionsArticleRepository;
use Heph\Tests\Faker\Entity\TermsConditionsArticle\TermsConditionsArticleFaker;
use Heph\Tests\Functional\HephFunctionalTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

#[
    CoversClass(UpdateTermsConditionsArticle::class),
    CoversClass(TermsConditionsArticle::class),
    CoversClass(UpdateTermsConditionsArticleCommand::class),
    CoversClass(TermsConditionsArticleUpdateDto::class),
    CoversClass(ApiResponse::class),
    CoversClass(ApiResponseFactory::class),
    CoversClass(ApiResponseData::class),
    CoversClass(ApiResponseLink::class),
    CoversClass(ApiResponseMessage::class),
    CoversClass(ApiResponseMeta::class),
    CoversClass(ListError::class),
    CoversClass(HephSerializer::class),
    CoversClass(UpdateTermsConditionsArticleCommand::class),
    CoversClass(TermsConditionsArticleRepository::class),
    CoversClass(ValueObjectNormalizer::class),
    CoversClass(InfoDescriptionModel::class),
    CoversClass(DescriptionValueObject::class),
    CoversClass(LibelleValueObject::class),
    CoversClass(TermsConditions::class),
    CoversClass(TermsConditionsArticleArticle::class),
    CoversClass(TermsConditionsArticleTitle::class),
    CoversClass(TermsConditionsArticleTitleType::class),
    CoversClass(TermsConditionsArticleArticleType::class),
    CoversClass(LibelleType::class),
    CoversClass(DescriptionType::class)
]
class UpdateTermsConditionsArticleTest extends HephFunctionalTestCase
{
    use InteractsWithMessenger;

    private KernelBrowser $client;
    private EntityManagerInterface $entityManager;
    private TermsConditionsArticleRepository $repository;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->client = $this->createClient();
        $this->entityManager = $this->getEntityManager();
        $this->entityManager->getConnection()->beginTransaction();
        $this->repository = $this->entityManager->getRepository(TermsConditionsArticle::class);
    }

    /**
     * @throws Exception
     */
    protected function tearDown(): void
    {
        $conn = $this->entityManager->getConnection();
        if ($conn->isTransactionActive()) {
            $conn->rollBack();
        }

        parent::tearDown();
    }

    /**
     * @throws Exception
     */
    public function testDoctrineConfiguration(): void
    {
        $connection = $this->entityManager->getConnection();
        self::assertTrue($connection->isConnected(), 'La connexion à la base de données est inactive');
    }

    public function testInvokeReturnsExpectedResponse(): void
    {
        $termsConditionsArticle = TermsConditionsArticleFaker::new();
        $this->entityManager->persist($termsConditionsArticle);
        $this->entityManager->flush();

        $termsConditionsArticleToUpdate = $this->repository->findOneBy([]);

        $updatePayload = json_encode([
            'title' => 'titre mis a jour',
            'article' => 'article mis a jour',
        ]);

        $this->client->request('PUT', '/api/terms-conditions-article/'.(string) $termsConditionsArticleToUpdate->id(), [], [], [], $updatePayload);
        $responseContent = $this->client->getResponse()->getContent();

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertJson($responseContent);

        $response = json_decode($responseContent, true);
        self::assertArrayHasKey('data', $response);
    }

    public function testInvokeReturnResponseSuccess(): void
    {
        $termsConditionsArticle = TermsConditionsArticleFaker::new();
        $this->entityManager->persist($termsConditionsArticle);
        $this->entityManager->flush();
        $termsConditionsArticleToUpdate = $this->repository->findOneBy([]);

        $updatePayload = json_encode([
            'title' => 'titre mis a jour',
            'article' => 'article mis a jour',
        ]);

        $this->client->request('PUT', '/api/terms-conditions-article/'.(string) $termsConditionsArticleToUpdate->id(), [], [], [], $updatePayload);
        $responseContent = $this->client->getResponse()->getContent();

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertJson($responseContent);

        $response = json_decode($responseContent, true);
        self::assertArrayHasKey('data', $response);
        self::assertArrayHasKey('message', $response['data']);
        self::assertEquals('La mise à jour a été prise en compte.', $response['data']['message']);
    }
}
