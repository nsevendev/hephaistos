<?php

declare(strict_types=1);

namespace Heph\Tests\Functional\Controller\Api\EngineRemap;

use Heph\Controller\Api\EngineRemap\GetFirstEngineRemap;
use Heph\Entity\EngineRemap\Dto\EngineRemapDto;
use Heph\Entity\EngineRemap\EngineRemap;
use Heph\Entity\InfoDescriptionModel\Dto\InfoDescriptionModelDto;
use Heph\Entity\InfoDescriptionModel\InfoDescriptionModel;
use Heph\Infrastructure\ApiResponse\ApiResponse;
use Heph\Infrastructure\ApiResponse\ApiResponseFactory;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseData;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseLink;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseMessage;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseMeta;
use Heph\Infrastructure\ApiResponse\Exception\Error\ListError;
use Heph\Infrastructure\Serializer\HephSerializer;
use Heph\Message\Query\EngineRemap\GetFirstEngineRemapHandler;
use Heph\Repository\EngineRemap\EngineRemapRepository;
use Heph\Tests\Faker\Entity\EngineRemap\EngineRemapFaker;
use Heph\Tests\Functional\HephFunctionalTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;

#[
    CoversClass(GetFirstEngineRemap::class),
    CoversClass(ApiResponse::class),
    CoversClass(ApiResponseFactory::class),
    CoversClass(ApiResponseData::class),
    CoversClass(ApiResponseLink::class),
    CoversClass(ApiResponseMessage::class),
    CoversClass(ApiResponseMeta::class),
    CoversClass(ListError::class),
    CoversClass(EngineRemapDto::class),
    CoversClass(HephSerializer::class),
    CoversClass(GetFirstEngineRemapHandler::class),
    CoversClass(EngineRemapRepository::class),
    CoversClass(EngineRemap::class),
    CoversClass(InfoDescriptionModelDto::class),
    CoversClass(InfoDescriptionModel::class)
]
class GetFirstEngineRemapTest extends HephFunctionalTestCase
{
    private KernelBrowser $client;

    public function setUp(): void
    {
        $this->client = self::createClient();
    }

    public function testInvokeReturnsExpectedResponse(): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->getConnection()->beginTransaction();

        $engineRemap = EngineRemapFaker::new();

        $entityManager->persist($engineRemap);
        $entityManager->flush();

        $this->client->request('GET', '/api/engine-remap');

        $content = $this->client->getResponse()->getContent();

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertJson($content);

        $response = json_decode($content, true);

        self::assertArrayHasKey('data', $response);
    }

    public function testCreateAndRetrieveFirstEngineRemap(): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->getConnection()->beginTransaction();

        $engineRemap = EngineRemapFaker::new();

        $entityManager->persist($engineRemap);
        $entityManager->flush();

        $retrievedEngineRemap = $entityManager->getRepository(EngineRemap::class)->find($engineRemap->id());
        self::assertNotNull($retrievedEngineRemap, 'Entity non trouvée en bdd.');

        $this->client->request('GET', '/api/engine-remap');

        $content = $this->client->getResponse()->getContent();

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertJson($content);

        $response = json_decode($content, true);
        self::assertArrayHasKey('statusCode', $response);
        self::assertSame(200, $response['statusCode']);  // Vérification du statusCode
        self::assertSame('Success', $response['message']);

        $retrievedEngineRemap = $response['data'];
        self::assertArrayHasKey('id', $retrievedEngineRemap);
        self::assertArrayHasKey('infoDescriptionModel', $retrievedEngineRemap);
        self::assertArrayHasKey('createdAt', $retrievedEngineRemap);
        self::assertArrayHasKey('updatedAt', $retrievedEngineRemap);

        $entityManager->rollback();
    }
}
