<?php

declare(strict_types=1);

namespace Heph\Tests\Functional\Controller\Api\Users;

use Doctrine\ORM\EntityManagerInterface;
use Heph\Controller\Api\Users\LoginUsers;
use Heph\Entity\InfoDescriptionModel\Dto\InfoDescriptionModelDto;
use Heph\Entity\InfoDescriptionModel\InfoDescriptionModel;
use Heph\Entity\Users\Dto\UsersDto;
use Heph\Entity\Users\Dto\UsersGetOneByIdDto;
use Heph\Entity\Users\Dto\UsersLoginDto;
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
use Heph\Infrastructure\ApiResponse\Exception\Custom\AbstractApiResponseException;
use Heph\Infrastructure\ApiResponse\Exception\Custom\Users\UsersInvalidArgumentException;
use Heph\Infrastructure\ApiResponse\Exception\Error\Error;
use Heph\Infrastructure\ApiResponse\Exception\Error\ListError;
use Heph\Infrastructure\ApiResponse\Exception\Event\ApiResponseExceptionListener;
use Heph\Infrastructure\Doctrine\Types\Users\UsersPasswordType;
use Heph\Infrastructure\Doctrine\Types\Users\UsersRoleType;
use Heph\Infrastructure\Doctrine\Types\Users\UsersUsernameType;
use Heph\Infrastructure\Serializer\HephSerializer;
use Heph\Infrastructure\Serializer\Normalizer\ValueObjectNormalizer;
use Heph\Message\Query\Users\LoginUsersHandler;
use Heph\Message\Query\Users\LoginUsersQuery;
use Heph\Repository\Users\UsersRepository;
use Heph\Tests\Functional\HephFunctionalTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[
    CoversClass(LoginUsers::class),
    CoversClass(ApiResponse::class),
    CoversClass(ApiResponseFactory::class),
    CoversClass(ApiResponseData::class),
    CoversClass(ApiResponseLink::class),
    CoversClass(ApiResponseMessage::class),
    CoversClass(ApiResponseMeta::class),
    CoversClass(ListError::class),
    CoversClass(UsersDto::class),
    CoversClass(HephSerializer::class),
    CoversClass(LoginUsersHandler::class),
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
    CoversClass(LoginUsersQuery::class),
    CoversClass(UsersLoginDto::class),
    CoversClass(ValueObjectNormalizer::class),
    CoversClass(AbstractApiResponseException::class),
    CoversClass(UsersInvalidArgumentException::class),
    CoversClass(Error::class),
    CoversClass(ApiResponseExceptionListener::class),
]
class LoginUsersTest extends HephFunctionalTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $hasher;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->client = $this->createClient();
        $this->entityManager = $this->getEntityManager();
        $this->entityManager->getConnection()->beginTransaction();
        $this->hasher = self::getContainer()->get(UserPasswordHasherInterface::class);
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
        $user = new Users(
            new UsersUsername('username test'),
            new UsersPassword('password test'),
            new UsersRole('ROLE_ADMIN'),
        );
        $hashed = $this->hasher->hashPassword($user, (string) $user->getPassword());
        $user->setPassword(new UsersPassword($hashed));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $payload = json_encode([
            'username' => 'username test',
            'password' => 'password test',
        ]);

        $this->client->request('POST', '/api/login', [], [], [], $payload);

        $content = $this->client->getResponse()->getContent();
        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertJson($content);

        $response = json_decode($content, true);

        self::assertArrayHasKey('data', $response);
    }

    public function testInvokeReturnsExpectedUsersInvalidArgument(): void
    {
        $payload = json_encode([
            'username' => 'username test',
            'password' => 'password test',
        ]);

        $this->client->request('POST', '/api/login', [], [], ['CONTENT_TYPE' => 'application/json'], $payload);

        $content = $this->client->getResponse()->getContent();

        self::assertResponseStatusCodeSame(422);
        self::assertJson($content);

        $response = json_decode($content, true);

        self::assertArrayHasKey('message', $response);
        self::assertSame("Nom d'utilisateur ou mot de passe incorrect.", $response['message']);
    }
}
