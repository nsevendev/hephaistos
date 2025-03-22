<?php

declare(strict_types=1);

namespace Heph\Tests\Functional\Controller\Api\EngineRemap;

use Doctrine\ORM\EntityManagerInterface;
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
    private EntityManagerInterface $entityManager;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->client = $this->createClient();
        $this->entityManager = $this->getEntityManager();
        $this->entityManager->getConnection()->beginTransaction();
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

    public function testRetrieveFirstEngineRemapWithEmptyBdd(): void
    {
        $this->client->request('GET', '/api/engine-remap');

        $content = $this->client->getResponse()->getContent();
        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertJson($content);

        $response = json_decode($content, true);
        self::assertArrayHasKey('statusCode', $response);
        self::assertSame(200, $response['statusCode']);
        self::assertSame('Success', $response['message']);
        self::assertNull($response['data']);
    }

    public function testInvokeReturnsExpectedResponse(): void
    {
        $engineRemap = EngineRemapFaker::new();
        $this->entityManager->persist($engineRemap);
        $this->entityManager->flush();

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
        $engineRemap = EngineRemapFaker::new();

        $this->entityManager->persist($engineRemap);
        $this->entityManager->flush();

        $retrievedEngineRemap = $this->entityManager->getRepository(EngineRemap::class)->find($engineRemap->id());
        self::assertNotNull($retrievedEngineRemap, 'Entity non trouvée en bdd.');

        $this->client->request('GET', '/api/engine-remap');

        $content = $this->client->getResponse()->getContent();

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertJson($content);

        $response = json_decode($content, true);
        self::assertArrayHasKey('statusCode', $response);
        self::assertSame(200, $response['statusCode']);
        self::assertSame('Success', $response['message']);

        $retrievedEngineRemap = $response['data'];
        self::assertArrayHasKey('id', $retrievedEngineRemap);
        self::assertArrayHasKey('infoDescriptionModel', $retrievedEngineRemap);
        self::assertArrayHasKey('createdAt', $retrievedEngineRemap);
        self::assertArrayHasKey('updatedAt', $retrievedEngineRemap);
    }
}
