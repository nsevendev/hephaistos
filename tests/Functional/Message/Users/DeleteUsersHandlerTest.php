<?php

declare(strict_types=1);

namespace Functional\Message\Users;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Heph\Entity\Users\Dto\UsersDeleteDto;
use Heph\Entity\Users\Users;
use Heph\Entity\Users\ValueObject\UsersPassword;
use Heph\Entity\Users\ValueObject\UsersRole;
use Heph\Entity\Users\ValueObject\UsersUsername;
use Heph\Infrastructure\ApiResponse\Exception\Custom\AbstractApiResponseException;
use Heph\Infrastructure\ApiResponse\Exception\Custom\Users\UsersBadRequestException;
use Heph\Infrastructure\ApiResponse\Exception\Error\Error;
use Heph\Infrastructure\Doctrine\Types\Users\UsersPasswordType;
use Heph\Infrastructure\Doctrine\Types\Users\UsersRoleType;
use Heph\Infrastructure\Doctrine\Types\Users\UsersUsernameType;
use Heph\Infrastructure\Mercure\MercurePublish;
use Heph\Message\Command\Users\DeleteUsersCommand;
use Heph\Message\Command\Users\DeleteUsersHandler;
use Heph\Repository\Users\UsersRepository;
use Heph\Tests\Faker\Entity\Users\UsersFaker;
use Heph\Tests\Functional\HephFunctionalTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Uid\Uuid;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

#[
    CoversClass(UsersRepository::class),
    CoversClass(Users::class),
    CoversClass(DeleteUsersCommand::class),
    CoversClass(DeleteUsersHandler::class),
    CoversClass(MercurePublish::class),
    CoversClass(UsersDeleteDto::class),
    CoversClass(AbstractApiResponseException::class),
    CoversClass(UsersBadRequestException::class),
    CoversClass(Error::class),
    CoversClass(UsersUsername::class),
    CoversClass(UsersUsernameType::class),
    CoversClass(UsersPassword::class),
    CoversClass(UsersPasswordType::class),
    CoversClass(UsersRole::class),
    CoversClass(UsersRoleType::class),
]
class DeleteUsersHandlerTest extends HephFunctionalTestCase
{
    use InteractsWithMessenger;

    private EntityManagerInterface $entityManager;

    private UsersRepository $repository;

    private DeleteUsersHandler $handler;

    // private MercurePublish $mercurePublish;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        self::bootKernel();
        $this->entityManager = self::getEntityManager();
        $this->entityManager->getConnection()->beginTransaction();

        $this->repository = $this->entityManager->getRepository(Users::class);
    }

    /**
     * @throws Exception
     */
    protected function tearDown(): void
    {
        $conn = $this->getEntityManager()->getConnection();

        if ($conn->isTransactionActive()) {
            $conn->rollBack();
        }
    }

    public function testDoctrineConfiguration(): void
    {
        $connection = self::getEntityManager()->getConnection();
        self::assertTrue($connection->isConnected(), 'La connexion à la base de données est inactive');
    }

    public function testDeleteUsers(): void
    {
        $users = UsersFaker::new();

        $this->entityManager->persist($users);
        $this->entityManager->flush();

        $bus = self::getContainer()->get('messenger.default_bus');
        $command = new DeleteUsersCommand($users->id()->toString());
        $bus->dispatch($command);
        $this->flush();

        $this->transport('async')->queue()->assertNotEmpty();
        $this->transport('async')->queue()->assertCount(1);
        $this->transport('async')->process(1);
        $this->transport('async')->queue()->assertCount(0);
    }

    public function testDeleteUsersNotExist(): void
    {
        $this->expectException(HandlerFailedException::class);
        $id = Uuid::v7()->toString();
        $this->transport('othersync')->send(new DeleteUsersCommand($id));
        $this->transport('othersync')->process(1);
        $this->transport('othersync')->catchExceptions();
    }
}
