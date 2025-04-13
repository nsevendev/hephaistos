<?php

declare(strict_types=1);

namespace Heph\Tests\Functional\Controller\Api\WorkShop;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Heph\Controller\Api\WorkShop\UpdateWorkShop;
use Heph\Entity\InfoDescriptionModel\Dto\InfoDescriptionModelCreateDto;
use Heph\Entity\InfoDescriptionModel\InfoDescriptionModel;
use Heph\Entity\Shared\ValueObject\DescriptionValueObject;
use Heph\Entity\Shared\ValueObject\LibelleValueObject;
use Heph\Entity\WorkShop\Dto\WorkShopUpdateDto;
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
use Heph\Infrastructure\Serializer\Normalizer\ValueObjectNormalizer;
use Heph\Message\Command\WorkShop\UpdateWorkShopCommand;
use Heph\Repository\WorkShop\WorkShopRepository;
use Heph\Tests\Faker\Entity\WorkShop\WorkShopFaker;
use Heph\Tests\Functional\HephFunctionalTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

#[
    CoversClass(UpdateWorkShop::class),
    CoversClass(WorkShop::class),
    CoversClass(UpdateWorkShopCommand::class),
    CoversClass(WorkShopUpdateDto::class),
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
    CoversClass(UpdateWorkShopCommand::class),
    CoversClass(WorkShopRepository::class),
    CoversClass(LibelleType::class),
    CoversClass(DescriptionType::class),
    CoversClass(InfoDescriptionModelCreateDto::class),
    CoversClass(ValueObjectNormalizer::class)

]
class UpdateWorkShopTest extends HephFunctionalTestCase
{
    use InteractsWithMessenger;

    private KernelBrowser $client;
    private EntityManagerInterface $entityManager;
    private WorkShopRepository $repository;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->client = $this->createClient();
        $this->entityManager = $this->getEntityManager();
        $this->entityManager->getConnection()->beginTransaction();
        $this->repository = $this->entityManager->getRepository(WorkShop::class);
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
        $workShop = WorkShopFaker::new();
        $this->entityManager->persist($workShop);
        $this->entityManager->flush();

        $workShopToUpdate = $this->repository->findOneBy([]);

        $updatePayload = json_encode([
            'info_description_model' => [
                'libelle' => 'ceci est un libelle test',
                'description' => 'ceci est une description test',
            ],
        ]);

        $this->client->request('PUT', '/api/workshop/'.(string) $workShopToUpdate->id(), [], [], [], $updatePayload);
        $responseContent = $this->client->getResponse()->getContent();

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertJson($responseContent);

        $response = json_decode($responseContent, true);
        self::assertArrayHasKey('data', $response);
    }

    public function testInvokeReturnResponseSuccess(): void
    {
        $workShop = WorkShopFaker::new();
        $this->entityManager->persist($workShop);
        $this->entityManager->flush();
        $workShopToUpdate = $this->repository->findOneBy([]);

        $updatePayload = json_encode([
            'info_description_model' => [
                'libelle' => 'ceci est un libelle test',
                'description' => 'ceci est une description test',
            ],
        ]);

        $this->client->request('PUT', '/api/workshop/'.(string) $workShopToUpdate->id(), [], [], [], $updatePayload);
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
