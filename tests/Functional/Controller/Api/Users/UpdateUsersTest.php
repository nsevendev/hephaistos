<?php

declare(strict_types=1);

namespace Heph\Tests\Functional\Controller\Api\Users;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Heph\Controller\Api\Users\UpdateUsers;
use Heph\Entity\InfoDescriptionModel\Dto\InfoDescriptionModelCreateDto;
use Heph\Entity\InfoDescriptionModel\InfoDescriptionModel;
use Heph\Entity\Users\Dto\UsersUpdateDto;
use Heph\Entity\Users\Users;
use Heph\Entity\Users\ValueObject\UsersPassword;
use Heph\Entity\Users\ValueObject\UsersRole;
use Heph\Entity\Users\ValueObject\UsersUsername;
use Heph\Infrastructure\ApiResponse\ApiResponse;
use Heph\Infrastructure\ApiResponse\ApiResponseFactory;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseData;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseLink;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseMessage;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseMeta;
use Heph\Infrastructure\ApiResponse\Exception\Error\ListError;
use Heph\Infrastructure\Doctrine\Types\Users\UsersPasswordType;
use Heph\Infrastructure\Doctrine\Types\Users\UsersRoleType;
use Heph\Infrastructure\Doctrine\Types\Users\UsersUsernameType;
use Heph\Infrastructure\Serializer\HephSerializer;
use Heph\Infrastructure\Serializer\Normalizer\ValueObjectNormalizer;
use Heph\Message\Command\Users\UpdateUsersCommand;
use Heph\Repository\Users\UsersRepository;
use Heph\Tests\Faker\Entity\Users\UsersFaker;
use Heph\Tests\Functional\HephFunctionalTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

#[
    CoversClass(UpdateUsers::class),
    CoversClass(Users::class),
    CoversClass(UpdateUsersCommand::class),
    CoversClass(UsersUpdateDto::class),
    CoversClass(InfoDescriptionModel::class),
    CoversClass(ApiResponse::class),
    CoversClass(ApiResponseFactory::class),
    CoversClass(ApiResponseData::class),
    CoversClass(ApiResponseLink::class),
    CoversClass(ApiResponseMessage::class),
    CoversClass(ApiResponseMeta::class),
    CoversClass(ListError::class),
    CoversClass(HephSerializer::class),
    CoversClass(UpdateUsersCommand::class),
    CoversClass(UsersRepository::class),
    CoversClass(ValueObjectNormalizer::class),
    CoversClass(UsersRole::class),
    CoversClass(UsersPassword::class),
    CoversClass(UsersUsername::class),
    CoversClass(UsersRoleType::class),
    CoversClass(UsersPasswordType::class),
    CoversClass(UsersUsernameType::class),
    CoversClass(InfoDescriptionModelCreateDto::class),
]
class UpdateUsersTest extends HephFunctionalTestCase
{
    use InteractsWithMessenger;

    private KernelBrowser $client;
    private EntityManagerInterface $entityManager;
    private UsersRepository $repository;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->client = $this->createClient();
        $this->entityManager = $this->getEntityManager();
        $this->entityManager->getConnection()->beginTransaction();
        $this->repository = $this->entityManager->getRepository(Users::class);
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
        $users = UsersFaker::new();
        $this->entityManager->persist($users);
        $this->entityManager->flush();

        $usersToUpdate = $this->repository->findOneBy([]);

        $updatePayload = json_encode([
            'username' => 'username updated',
            'password' => 'password updated',
            'role' => 'ROLE_EMPLOYEE',
        ]);

        $this->client->request('PUT', '/api/users/'.(string) $usersToUpdate->id(), [], [], [], $updatePayload);
        $responseContent = $this->client->getResponse()->getContent();

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertJson($responseContent);

        $response = json_decode($responseContent, true);
        self::assertArrayHasKey('data', $response);
    }

    public function testInvokeReturnResponseSuccess(): void
    {
        $users = UsersFaker::new();
        $this->entityManager->persist($users);
        $this->entityManager->flush();
        $usersToUpdate = $this->repository->findOneBy([]);

        $updatePayload = json_encode([
            'username' => 'username updated',
            'password' => 'password updated',
            'role' => 'ROLE_EMPLOYEE',
        ]);

        $this->client->request('PUT', '/api/users/'.(string) $usersToUpdate->id(), [], [], [], $updatePayload);
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
