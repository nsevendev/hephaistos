<?php

declare(strict_types=1);

namespace Heph\Tests\Functional\Controller\Api\Users;

use Doctrine\ORM\EntityManagerInterface;
use Heph\Controller\Api\Users\GetUsersById;
use Heph\Entity\InfoDescriptionModel\Dto\InfoDescriptionModelDto;
use Heph\Entity\InfoDescriptionModel\InfoDescriptionModel;
use Heph\Entity\Users\Dto\UsersDto;
use Heph\Entity\Users\Dto\UsersGetOneByIdDto;
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
use Heph\Message\Query\Users\GetUsersByIdHandler;
use Heph\Message\Query\Users\GetUsersByIdQuery;
use Heph\Repository\Users\UsersRepository;
use Heph\Tests\Faker\Entity\Users\UsersFaker;
use Heph\Tests\Functional\HephFunctionalTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;

#[
    CoversClass(GetUsersById::class),
    CoversClass(ApiResponse::class),
    CoversClass(ApiResponseFactory::class),
    CoversClass(ApiResponseData::class),
    CoversClass(ApiResponseLink::class),
    CoversClass(ApiResponseMessage::class),
    CoversClass(ApiResponseMeta::class),
    CoversClass(ListError::class),
    CoversClass(UsersDto::class),
    CoversClass(HephSerializer::class),
    CoversClass(GetUsersByIdHandler::class),
    CoversClass(UsersRepository::class),
    CoversClass(Users::class),
    CoversClass(InfoDescriptionModelDto::class),
    CoversClass(InfoDescriptionModel::class),
    CoversClass(UsersRole::class),
    CoversClass(UsersUsername::class),
    CoversClass(UsersPassword::class),
    CoversClass(UsersRoleType::class),
    CoversClass(UsersUsernameType::class),
    CoversClass(UsersPasswordType::class),
    CoversClass(UsersGetOneByIdDto::class),
    CoversClass(GetUsersByIdQuery::class),
]
class GetUsersByIdTest extends HephFunctionalTestCase
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

    public function testInvokeReturnsExpectedResponse(): void
    {
        $users = UsersFaker::new();
        $this->entityManager->persist($users);
        $this->entityManager->flush();

        $this->client->request('GET', '/api/users/'.(string) $users->id());

        $content = $this->client->getResponse()->getContent();
        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertJson($content);

        $response = json_decode($content, true);

        self::assertArrayHasKey('data', $response);
    }

    public function testCreateAndRetrieveUsersById(): void
    {
        $users = UsersFaker::new();

        $this->entityManager->persist($users);
        $this->entityManager->flush();

        $retrievedUsers = $this->entityManager->getRepository(Users::class)->find($users->id());
        self::assertNotNull($retrievedUsers, 'Entity non trouvée en bdd.');

        $this->client->request('GET', '/api/users/'.(string) $users->id());

        $content = $this->client->getResponse()->getContent();

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertJson($content);

        $response = json_decode($content, true);
        self::assertArrayHasKey('statusCode', $response);
        self::assertSame(200, $response['statusCode']);
        self::assertSame('Success', $response['message']);

        $retrievedUsers = $response['data'];
        self::assertArrayHasKey('id', $retrievedUsers);
        self::assertArrayHasKey('username', $retrievedUsers);
        self::assertArrayHasKey('role', $retrievedUsers);
        self::assertArrayHasKey('createdAt', $retrievedUsers);
        self::assertArrayHasKey('updatedAt', $retrievedUsers);
        self::assertArrayNotHasKey('password', $retrievedUsers);
    }
}
