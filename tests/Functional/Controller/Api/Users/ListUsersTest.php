<?php

declare(strict_types=1);

namespace Heph\Tests\Functional\Controller\Api\Users;

use Doctrine\DBAL\Exception;
use Heph\Controller\Api\Users\ListUsers;
use Heph\Entity\Users\Dto\UsersDto;
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
use Heph\Infrastructure\ApiResponse\Exception\Custom\Users\UsersInvalidArgumentException;
use Heph\Infrastructure\ApiResponse\Exception\Error\ListError;
use Heph\Infrastructure\Doctrine\Types\Users\UsersPasswordType;
use Heph\Infrastructure\Doctrine\Types\Users\UsersRoleType;
use Heph\Infrastructure\Doctrine\Types\Users\UsersUsernameType;
use Heph\Infrastructure\Serializer\HephSerializer;
use Heph\Message\Query\Users\GetListUsersHandler;
use Heph\Repository\Users\UsersRepository;
use Heph\Tests\Functional\HephFunctionalTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[
    CoversClass(ListUsers::class),
    CoversClass(ApiResponse::class),
    CoversClass(ApiResponseFactory::class),
    CoversClass(ApiResponseData::class),
    CoversClass(ApiResponseLink::class),
    CoversClass(ApiResponseMessage::class),
    CoversClass(ApiResponseMeta::class),
    CoversClass(ListError::class),
    CoversClass(UsersDto::class),
    CoversClass(HephSerializer::class),
    CoversClass(GetListUsersHandler::class),
    CoversClass(UsersRepository::class),
    CoversClass(Users::class),
    CoversClass(UsersUsername::class),
    CoversClass(UsersUsernameType::class),
    CoversClass(UsersPassword::class),
    CoversClass(UsersPasswordType::class),
    CoversClass(UsersRole::class),
    CoversClass(UsersRoleType::class),
]
class ListUsersTest extends HephFunctionalTestCase
{
    private KernelBrowser $client;
    private UserPasswordHasherInterface $hasher;

    public function setUp(): void
    {
        $this->client = self::createClient();
        $this->hasher = self::getContainer()->get(UserPasswordHasherInterface::class);
    }

    public function testInvokeReturnsExpectedResponse(): void
    {
        $this->client->request('GET', '/api/list-users');

        $content = $this->client->getResponse()->getContent();

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertJson((string) $content);

        $response = json_decode((string) $content, true);

        self::assertArrayHasKey('data', $response);
    }

    /**
     * @throws Exception
     * @throws UsersInvalidArgumentException
     */
    public function testCreateAndRetrieveUsers(): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->getConnection()->beginTransaction();

        $user = new Users(
            new UsersUsername('username test'),
            new UsersPassword('password test'),
            new UsersRole('ROLE_ADMIN'),
        );
        $hashed = $this->hasher->hashPassword($user, (string) $user->getPassword());
        $user->setPassword(new UsersPassword($hashed));

        $entityManager->persist($user);
        $entityManager->flush();

        $this->client->request('GET', '/api/list-users');

        $content = $this->client->getResponse()->getContent();

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertJson((string) $content);

        $response = json_decode((string) $content, true);
        self::assertIsArray($response);
        self::assertArrayHasKey('data', $response);
        self::assertNotEmpty($response['data']);

        $retrievedUsers = $response['data'][0];
        self::assertSame('username test', $retrievedUsers['username']);
        self::assertSame('ROLE_ADMIN', $retrievedUsers['role']);

        $entityManager->rollback();
    }
}
