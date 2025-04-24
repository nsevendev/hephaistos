<?php

declare(strict_types=1);

namespace Heph\Tests\Functional\Controller\Api\TermsConditionsArticle;

use Doctrine\DBAL\Exception;
use Heph\Controller\Api\TermsConditionsArticle\ListTermsConditionsArticle;
use Heph\Entity\InfoDescriptionModel\InfoDescriptionModel;
use Heph\Entity\Shared\ValueObject\DescriptionValueObject;
use Heph\Entity\Shared\ValueObject\LibelleValueObject;
use Heph\Entity\TermsConditions\TermsConditions;
use Heph\Entity\TermsConditionsArticle\Dto\TermsConditionsArticleDto;
use Heph\Entity\TermsConditionsArticle\TermsConditionsArticle;
use Heph\Entity\TermsConditionsArticle\ValueObject\TermsConditionsArticleArticle;
use Heph\Entity\TermsConditionsArticle\ValueObject\TermsConditionsArticleTitle;
use Heph\Infrastructure\ApiResponse\ApiResponse;
use Heph\Infrastructure\ApiResponse\ApiResponseFactory;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseData;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseLink;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseMessage;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseMeta;
use Heph\Infrastructure\ApiResponse\Exception\Custom\TermsConditionsArticle\TermsConditionsArticleInvalidArgumentException;
use Heph\Infrastructure\ApiResponse\Exception\Error\ListError;
use Heph\Infrastructure\Doctrine\Types\Shared\DescriptionType;
use Heph\Infrastructure\Doctrine\Types\Shared\LibelleType;
use Heph\Infrastructure\Doctrine\Types\TermsConditionsArticle\TermsConditionsArticleArticleType;
use Heph\Infrastructure\Doctrine\Types\TermsConditionsArticle\TermsConditionsArticleTitleType;
use Heph\Infrastructure\Serializer\HephSerializer;
use Heph\Message\Query\TermsConditionsArticle\GetListTermsConditionsArticleHandler;
use Heph\Repository\TermsConditionsArticle\TermsConditionsArticleRepository;
use Heph\Tests\Faker\Entity\TermsConditionsArticle\TermsConditionsArticleFaker;
use Heph\Tests\Functional\HephFunctionalTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;

#[
    CoversClass(ListTermsConditionsArticle::class),
    CoversClass(ApiResponse::class),
    CoversClass(ApiResponseFactory::class),
    CoversClass(ApiResponseData::class),
    CoversClass(ApiResponseLink::class),
    CoversClass(ApiResponseMessage::class),
    CoversClass(ApiResponseMeta::class),
    CoversClass(ListError::class),
    CoversClass(TermsConditionsArticleDto::class),
    CoversClass(HephSerializer::class),
    CoversClass(GetListTermsConditionsArticleHandler::class),
    CoversClass(TermsConditionsArticleRepository::class),
    CoversClass(TermsConditionsArticle::class),
    CoversClass(InfoDescriptionModel::class),
    CoversClass(DescriptionValueObject::class),
    CoversClass(LibelleValueObject::class),
    CoversClass(TermsConditions::class),
    CoversClass(TermsConditionsArticleArticle::class),
    CoversClass(TermsConditionsArticleTitle::class),
    CoversClass(DescriptionType::class),
    CoversClass(LibelleType::class),
    CoversClass(DescriptionValueObject::class),
    CoversClass(TermsConditionsArticleArticleType::class),
    CoversClass(TermsConditionsArticleTitleType::class),
]
class ListTermsConditionsArticleTest extends HephFunctionalTestCase
{
    private KernelBrowser $client;

    public function setUp(): void
    {
        $this->client = self::createClient();
    }

    public function testInvokeReturnsExpectedResponse(): void
    {
        $this->client->request('GET', '/api/list-terms-conditions-article');

        $content = $this->client->getResponse()->getContent();

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertJson((string) $content);

        $response = json_decode((string) $content, true);

        self::assertArrayHasKey('data', $response);
    }

    /**
     * @throws Exception
     * @throws TermsConditionsArticleInvalidArgumentException
     */
    public function testCreateAndRetrieveTermsConditionsArticle(): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->getConnection()->beginTransaction();

        $termsConditionsArticle = TermsConditionsArticleFaker::new();

        $entityManager->persist($termsConditionsArticle);
        $entityManager->flush();

        $this->client->request('GET', '/api/list-terms-conditions-article');

        $content = $this->client->getResponse()->getContent();

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertJson((string) $content);

        $response = json_decode((string) $content, true);
        self::assertIsArray($response);
        self::assertArrayHasKey('data', $response);
        self::assertNotEmpty($response['data']);

        $retrievedTermsConditionsArticle = $response['data'][0];
        self::assertArrayHasKey('id', $retrievedTermsConditionsArticle);
        self::assertArrayHasKey('termsConditions', $retrievedTermsConditionsArticle);
        self::assertArrayHasKey('title', $retrievedTermsConditionsArticle);
        self::assertArrayHasKey('article', $retrievedTermsConditionsArticle);
        self::assertArrayHasKey('createdAt', $retrievedTermsConditionsArticle);
        self::assertArrayHasKey('updatedAt', $retrievedTermsConditionsArticle);

        $entityManager->rollback();
    }
}
