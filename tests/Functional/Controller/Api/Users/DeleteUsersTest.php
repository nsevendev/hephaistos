<?php

declare(strict_types=1);

namespace Functional\Controller\Api\Users;

use Doctrine\DBAL\Exception;
use Heph\Controller\Api\Users\DeleteUsers;
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
use Heph\Infrastructure\ApiResponse\Exception\Error\ListError;
use Heph\Infrastructure\Doctrine\Types\Users\UsersPasswordType;
use Heph\Infrastructure\Doctrine\Types\Users\UsersRoleType;
use Heph\Infrastructure\Doctrine\Types\Users\UsersUsernameType;
use Heph\Infrastructure\Serializer\HephSerializer;
use Heph\Message\Command\Users\DeleteUsersCommand;
use Heph\Message\Query\Users\GetListUsersHandler;
use Heph\Repository\Users\UsersRepository;
use Heph\Tests\Faker\Entity\Users\UsersFaker;
use Heph\Tests\Functional\HephFunctionalTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;

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
    CoversClass(DeleteUsers::class),
    CoversClass(DeleteUsersCommand::class)
]
class DeleteUsersTest extends HephFunctionalTestCase
{
    private KernelBrowser $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    /**
     * @throws Exception
     */
    public function testCreateAndDeleteUsers(): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->getConnection()->beginTransaction();

        $users = UsersFaker::new();

        $entityManager->persist($users);
        $entityManager->flush();

        $this->client->request('DELETE', '/api/users/'.$users->id());

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        $entityManager->rollBack();
    }
}
