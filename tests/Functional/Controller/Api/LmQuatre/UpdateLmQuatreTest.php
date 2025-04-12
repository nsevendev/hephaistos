<?php

declare(strict_types=1);

namespace Heph\Tests\Functional\Controller\Api\LmQuatre;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Heph\Controller\Api\LmQuatre\UpdateLmQuatre;
use Heph\Entity\InfoDescriptionModel\Dto\InfoDescriptionModelCreateDto;
use Heph\Entity\InfoDescriptionModel\InfoDescriptionModel;
use Heph\Entity\LmQuatre\Dto\LmQuatreUpdateDto;
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
use Heph\Infrastructure\Serializer\Normalizer\ValueObjectNormalizer;
use Heph\Message\Command\LmQuatre\UpdateLmQuatreCommand;
use Heph\Repository\LmQuatre\LmQuatreRepository;
use Heph\Tests\Faker\Entity\LmQuatre\LmQuatreFaker;
use Heph\Tests\Functional\HephFunctionalTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

#[
    CoversClass(UpdateLmQuatre::class),
    CoversClass(LmQuatre::class),
    CoversClass(UpdateLmQuatreCommand::class),
    CoversClass(LmQuatreUpdateDto::class),
    CoversClass(InfoDescriptionModel::class),
    CoversClass(DescriptionValueObject::class),
    CoversClass(LibelleValueObject::class),
    CoversClass(ApiResponse::class),
    CoversClass(ApiResponseFactory::class),
    CoversClass(ApiResponseData::class),
    CoversClass(ApiResponseLink::class),
    CoversClass(ApiResponseMessage::class),
    CoversClass(ApiResponseMeta::class),
    CoversClass(ListError::class),
    CoversClass(HephSerializer::class),
    CoversClass(UpdateLmQuatreCommand::class),
    CoversClass(LmQuatreRepository::class),
    CoversClass(LibelleType::class),
    CoversClass(DescriptionType::class),
    CoversClass(ValueObjectNormalizer::class),
    CoversClass(LmQuatreOwner::class),
    CoversClass(LmQuatreEmail::class),
    CoversClass(LmQuatreAdresse::class),
    CoversClass(LmQuatrePhoneNumber::class),
    CoversClass(LmQuatreOwnerType::class),
    CoversClass(LmQuatreEmailType::class),
    CoversClass(LmQuatreAdresseType::class),
    CoversClass(LmQuatrePhoneNumberType::class),
    CoversClass(InfoDescriptionModelCreateDto::class),
]
class UpdateLmQuatreTest extends HephFunctionalTestCase
{
    use InteractsWithMessenger;

    private KernelBrowser $client;
    private EntityManagerInterface $entityManager;
    private LmQuatreRepository $repository;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->client = $this->createClient();
        $this->entityManager = $this->getEntityManager();
        $this->entityManager->getConnection()->beginTransaction();
        $this->repository = $this->entityManager->getRepository(LmQuatre::class);
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
        $lmQuatre = LmQuatreFaker::new();
        $this->entityManager->persist($lmQuatre);
        $this->entityManager->flush();

        $lmQuatreToUpdate = $this->repository->findOneBy([]);

        $updatePayload = json_encode([
            'info_description_model' => [
                'libelle' => 'ceci est un libelle test',
                'description' => 'ceci est une description test',
            ],
            'owner' => 'owner updated',
            'adresse' => 'adresse test',
            'email' => 'test@test.com',
            'phone_number' => '123456789',
            'company_create_date' => '2000-03-31',

        ]);

        $this->client->request('PUT', '/api/lm-quatre/'.(string) $lmQuatreToUpdate->id(), [], [], [], $updatePayload);
        $responseContent = $this->client->getResponse()->getContent();

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertJson($responseContent);

        $response = json_decode($responseContent, true);
        self::assertArrayHasKey('data', $response);
    }

    public function testInvokeReturnResponseSuccess(): void
    {
        $lmQuatre = LmQuatreFaker::new();
        $this->entityManager->persist($lmQuatre);
        $this->entityManager->flush();
        $lmQuatreToUpdate = $this->repository->findOneBy([]);

        $updatePayload = json_encode([
            'info_description_model' => [
                'libelle' => 'ceci est un libelle test',
                'description' => 'ceci est une description test',
            ],
            'owner' => 'owner updated',
            'adresse' => 'adresse test',
            'email' => 'test@test.com',
            'phone_number' => '123456789',
            'company_create_date' => '2000-03-31',

        ]);

        $this->client->request('PUT', '/api/lm-quatre/'.(string) $lmQuatreToUpdate->id(), [], [], [], $updatePayload);
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
