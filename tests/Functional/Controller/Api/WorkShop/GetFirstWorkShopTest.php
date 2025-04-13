<?php

declare(strict_types=1);

namespace Heph\Tests\Functional\Controller\Api\WorkShop;

use Doctrine\ORM\EntityManagerInterface;
use Heph\Controller\Api\WorkShop\GetFirstWorkShop;
use Heph\Entity\InfoDescriptionModel\Dto\InfoDescriptionModelDto;
use Heph\Entity\InfoDescriptionModel\InfoDescriptionModel;
use Heph\Entity\Shared\ValueObject\DescriptionValueObject;
use Heph\Entity\Shared\ValueObject\LibelleValueObject;
use Heph\Entity\WorkShop\Dto\WorkShopDto;
use Heph\Entity\WorkShop\WorkShop;
use Heph\Infrastructure\ApiResponse\ApiResponse;
use Heph\Infrastructure\ApiResponse\ApiResponseFactory;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseData;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseLink;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseMessage;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseMeta;
use Heph\Infrastructure\ApiResponse\Exception\Error\ListError;
use Heph\Infrastructure\Doctrine\Types\Shared\DescriptionType;
use Heph\Infrastructure\Doctrine\Types\Shared\LibelleType;
use Heph\Infrastructure\Serializer\HephSerializer;
use Heph\Message\Query\WorkShop\GetFirstWorkShopHandler;
use Heph\Repository\WorkShop\WorkShopRepository;
use Heph\Tests\Faker\Entity\WorkShop\WorkShopFaker;
use Heph\Tests\Functional\HephFunctionalTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;

#[
    CoversClass(GetFirstWorkShop::class),
    CoversClass(ApiResponse::class),
    CoversClass(ApiResponseFactory::class),
    CoversClass(ApiResponseData::class),
    CoversClass(ApiResponseLink::class),
    CoversClass(ApiResponseMessage::class),
    CoversClass(ApiResponseMeta::class),
    CoversClass(ListError::class),
    CoversClass(WorkShopDto::class),
    CoversClass(HephSerializer::class),
    CoversClass(GetFirstWorkShopHandler::class),
    CoversClass(WorkShopRepository::class),
    CoversClass(WorkShop::class),
    CoversClass(InfoDescriptionModelDto::class),
    CoversClass(LibelleValueObject::class),
    CoversClass(DescriptionValueObject::class),
    CoversClass(LibelleType::class),
    CoversClass(DescriptionType::class),
    CoversClass(InfoDescriptionModel::class),
    CoversClass(LibelleValueObject::class),
    CoversClass(DescriptionValueObject::class)
]
class GetFirstWorkShopTest extends HephFunctionalTestCase
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

    public function testRetrieveFirstWorkShopWithEmptyBdd(): void
    {
        $this->client->request('GET', '/api/workshop');

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
        $workShop = WorkShopFaker::new();
        $this->entityManager->persist($workShop);
        $this->entityManager->flush();

        $this->client->request('GET', '/api/workshop');

        $content = $this->client->getResponse()->getContent();
        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertJson($content);

        $response = json_decode($content, true);

        self::assertArrayHasKey('data', $response);
    }

    public function testCreateAndRetrieveFirstWorkShop(): void
    {
        $workShop = WorkShopFaker::new();

        $this->entityManager->persist($workShop);
        $this->entityManager->flush();

        $retrievedWorkShop = $this->entityManager->getRepository(WorkShop::class)->find($workShop->id());
        self::assertNotNull($retrievedWorkShop, 'Entity non trouvée en bdd.');

        $this->client->request('GET', '/api/workshop');

        $content = $this->client->getResponse()->getContent();

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertJson($content);

        $response = json_decode($content, true);
        self::assertArrayHasKey('statusCode', $response);
        self::assertSame(200, $response['statusCode']);
        self::assertSame('Success', $response['message']);

        $retrievedWorkShop = $response['data'];
        self::assertArrayHasKey('id', $retrievedWorkShop);
        self::assertArrayHasKey('infoDescriptionModel', $retrievedWorkShop);
        self::assertArrayHasKey('createdAt', $retrievedWorkShop);
        self::assertArrayHasKey('updatedAt', $retrievedWorkShop);
    }
}
