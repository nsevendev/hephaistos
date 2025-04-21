<?php

declare(strict_types=1);

namespace Heph\Tests\Functional\Controller\Api\TermsConditions;

use Doctrine\DBAL\Exception;
use Heph\Controller\Api\TermsConditions\ListTermsConditions;
use Heph\Entity\InfoDescriptionModel\Dto\InfoDescriptionModelDto;
use Heph\Entity\InfoDescriptionModel\InfoDescriptionModel;
use Heph\Entity\Shared\ValueObject\DescriptionValueObject;
use Heph\Entity\Shared\ValueObject\LibelleValueObject;
use Heph\Entity\TermsConditions\Dto\TermsConditionsDto;
use Heph\Entity\TermsConditions\TermsConditions;
use Heph\Infrastructure\ApiResponse\ApiResponse;
use Heph\Infrastructure\ApiResponse\ApiResponseFactory;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseData;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseLink;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseMessage;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseMeta;
use Heph\Infrastructure\ApiResponse\Exception\Custom\TermsConditions\TermsConditionsInvalidArgumentException;
use Heph\Infrastructure\ApiResponse\Exception\Error\ListError;
use Heph\Infrastructure\Doctrine\Types\Shared\DescriptionType;
use Heph\Infrastructure\Doctrine\Types\Shared\LibelleType;
use Heph\Infrastructure\Serializer\HephSerializer;
use Heph\Message\Query\TermsConditions\GetListTermsConditionsHandler;
use Heph\Repository\TermsConditions\TermsConditionsRepository;
use Heph\Tests\Faker\Entity\TermsConditions\TermsConditionsFaker;
use Heph\Tests\Functional\HephFunctionalTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;

#[
    CoversClass(ListTermsConditions::class),
    CoversClass(ApiResponse::class),
    CoversClass(ApiResponseFactory::class),
    CoversClass(ApiResponseData::class),
    CoversClass(ApiResponseLink::class),
    CoversClass(ApiResponseMessage::class),
    CoversClass(ApiResponseMeta::class),
    CoversClass(ListError::class),
    CoversClass(TermsConditionsDto::class),
    CoversClass(HephSerializer::class),
    CoversClass(GetListTermsConditionsHandler::class),
    CoversClass(TermsConditionsRepository::class),
    CoversClass(TermsConditions::class),
    CoversClass(DescriptionValueObject::class),
    CoversClass(DescriptionType::class),
    CoversClass(LibelleValueObject::class),
    CoversClass(LibelleType::class),
    CoversClass(InfoDescriptionModelDto::class),
    CoversClass(InfoDescriptionModel::class),
]
class ListTermsConditionsTest extends HephFunctionalTestCase
{
    private KernelBrowser $client;

    public function setUp(): void
    {
        $this->client = self::createClient();
    }

    public function testInvokeReturnsExpectedResponse(): void
    {
        $this->client->request('GET', '/api/list-terms-conditions');

        $content = $this->client->getResponse()->getContent();

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertJson((string) $content);

        $response = json_decode((string) $content, true);

        self::assertArrayHasKey('data', $response);
    }

    /**
     * @throws Exception
     * @throws TermsConditionsInvalidArgumentException
     */
    public function testCreateAndRetrieveTermsConditions(): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->getConnection()->beginTransaction();

        $ping = TermsConditionsFaker::new();

        $entityManager->persist($ping);
        $entityManager->flush();

        $this->client->request('GET', '/api/list-terms-conditions');

        $content = $this->client->getResponse()->getContent();

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertJson((string) $content);

        $response = json_decode((string) $content, true);
        self::assertIsArray($response);
        self::assertArrayHasKey('data', $response);
        self::assertNotEmpty($response['data']);

        $retrievedTermsConditions = $response['data'][0];
        self::assertArrayHasKey('id', $retrievedTermsConditions);
        self::assertArrayHasKey('infoDescriptionModel', $retrievedTermsConditions);
        self::assertArrayHasKey('createdAt', $retrievedTermsConditions);
        self::assertArrayHasKey('updatedAt', $retrievedTermsConditions);

        $entityManager->rollback();
    }
}
