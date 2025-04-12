<?php

declare(strict_types=1);

namespace Heph\Tests\Functional\Controller\Api\LmQuatre;

use Doctrine\ORM\EntityManagerInterface;
use Heph\Controller\Api\LmQuatre\GetFirstLmQuatre;
use Heph\Entity\InfoDescriptionModel\Dto\InfoDescriptionModelDto;
use Heph\Entity\InfoDescriptionModel\InfoDescriptionModel;
use Heph\Entity\LmQuatre\Dto\LmQuatreDto;
use Heph\Entity\LmQuatre\LmQuatre;
use Heph\Entity\LmQuatre\ValueObject\LmQuatreAdresse;
use Heph\Entity\LmQuatre\ValueObject\LmQuatreEmail;
use Heph\Entity\LmQuatre\ValueObject\LmQuatreOwner;
use Heph\Entity\LmQuatre\ValueObject\LmQuatrePhoneNumber;
use Heph\Entity\Shared\ValueObject\DescriptionValueObject;
use Heph\Entity\Shared\ValueObject\LibelleValueObject;
use Heph\Infrastructure\ApiResponse\ApiResponse;
use Heph\Infrastructure\ApiResponse\ApiResponseFactory;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseData;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseLink;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseMessage;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseMeta;
use Heph\Infrastructure\ApiResponse\Exception\Error\ListError;
use Heph\Infrastructure\Doctrine\Types\LmQuatre\LmQuatreAdresseType;
use Heph\Infrastructure\Doctrine\Types\LmQuatre\LmQuatreEmailType;
use Heph\Infrastructure\Doctrine\Types\LmQuatre\LmQuatreOwnerType;
use Heph\Infrastructure\Doctrine\Types\LmQuatre\LmQuatrePhoneNumberType;
use Heph\Infrastructure\Doctrine\Types\Shared\DescriptionType;
use Heph\Infrastructure\Doctrine\Types\Shared\LibelleType;
use Heph\Infrastructure\Serializer\HephSerializer;
use Heph\Message\Query\LmQuatre\GetFirstLmQuatreHandler;
use Heph\Repository\LmQuatre\LmQuatreRepository;
use Heph\Tests\Faker\Entity\LmQuatre\LmQuatreFaker;
use Heph\Tests\Functional\HephFunctionalTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;

#[
    CoversClass(GetFirstLmQuatre::class),
    CoversClass(ApiResponse::class),
    CoversClass(ApiResponseFactory::class),
    CoversClass(ApiResponseData::class),
    CoversClass(ApiResponseLink::class),
    CoversClass(ApiResponseMessage::class),
    CoversClass(ApiResponseMeta::class),
    CoversClass(ListError::class),
    CoversClass(LmQuatreDto::class),
    CoversClass(HephSerializer::class),
    CoversClass(GetFirstLmQuatreHandler::class),
    CoversClass(LmQuatreRepository::class),
    CoversClass(LmQuatre::class),
    CoversClass(InfoDescriptionModelDto::class),
    CoversClass(LibelleValueObject::class),
    CoversClass(DescriptionValueObject::class),
    CoversClass(LibelleType::class),
    CoversClass(DescriptionType::class),
    CoversClass(InfoDescriptionModel::class),
    CoversClass(LibelleValueObject::class),
    CoversClass(DescriptionValueObject::class),
    CoversClass(LmQuatreOwner::class),
    CoversClass(LmQuatreAdresse::class),
    CoversClass(LmQuatreEmail::class),
    CoversClass(LmQuatrePhoneNumber::class),
    CoversClass(LmQuatreOwnerType::class),
    CoversClass(LmQuatreAdresseType::class),
    CoversClass(LmQuatreEmailType::class),
    CoversClass(LmQuatrePhoneNumberType::class)
]
class GetFirstLmQuatreTest extends HephFunctionalTestCase
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

    public function testRetrieveFirstLmQuatreWithEmptyBdd(): void
    {
        $this->client->request('GET', '/api/lm-quatre');

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
        $lmQuatre = LmQuatreFaker::new();
        $this->entityManager->persist($lmQuatre);
        $this->entityManager->flush();

        $this->client->request('GET', '/api/lm-quatre');

        $content = $this->client->getResponse()->getContent();
        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertJson($content);

        $response = json_decode($content, true);

        self::assertArrayHasKey('data', $response);
    }

    public function testCreateAndRetrieveFirstLmQuatre(): void
    {
        $lmQuatre = LmQuatreFaker::new();

        $this->entityManager->persist($lmQuatre);
        $this->entityManager->flush();

        $retrievedLmQuatre = $this->entityManager->getRepository(LmQuatre::class)->find($lmQuatre->id());
        self::assertNotNull($retrievedLmQuatre, 'Entity non trouvée en bdd.');

        $this->client->request('GET', '/api/lm-quatre');

        $content = $this->client->getResponse()->getContent();

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertJson($content);

        $response = json_decode($content, true);
        self::assertArrayHasKey('statusCode', $response);
        self::assertSame(200, $response['statusCode']);
        self::assertSame('Success', $response['message']);

        $retrievedLmQuatre = $response['data'];
        self::assertArrayHasKey('id', $retrievedLmQuatre);
        self::assertArrayHasKey('infoDescriptionModel', $retrievedLmQuatre);
        self::assertArrayHasKey('owner', $retrievedLmQuatre);
        self::assertArrayHasKey('adresse', $retrievedLmQuatre);
        self::assertArrayHasKey('email', $retrievedLmQuatre);
        self::assertArrayHasKey('phoneNumber', $retrievedLmQuatre);
        self::assertArrayHasKey('companyCreateDate', $retrievedLmQuatre);
        self::assertArrayHasKey('createdAt', $retrievedLmQuatre);
        self::assertArrayHasKey('updatedAt', $retrievedLmQuatre);
    }
}
