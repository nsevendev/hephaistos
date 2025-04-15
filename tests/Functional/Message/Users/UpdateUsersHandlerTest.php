<?php

declare(strict_types=1);

namespace Heph\Tests\Functional\Message\Users;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Heph\Entity\Users\Dto\UsersUpdateDto;
use Heph\Entity\Users\Users;
use Heph\Entity\Users\ValueObject\UsersPassword;
use Heph\Entity\Users\ValueObject\UsersRole;
use Heph\Entity\Users\ValueObject\UsersUsername;
use Heph\Infrastructure\Doctrine\Types\Users\UsersPasswordType;
use Heph\Infrastructure\Doctrine\Types\Users\UsersRoleType;
use Heph\Infrastructure\Doctrine\Types\Users\UsersUsernameType;
use Heph\Message\Command\Users\UpdateUsersCommand;
use Heph\Message\Command\Users\UpdateUsersHandler;
use Heph\Repository\Users\UsersRepository;
use Heph\Tests\Faker\Dto\Users\UsersUpdateDtoFaker;
use Heph\Tests\Faker\Entity\Users\UsersFaker;
use Heph\Tests\Functional\HephFunctionalTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

#[
    CoversClass(UsersRepository::class),
    CoversClass(Users::class),
    CoversClass(UpdateUsersCommand::class),
    CoversClass(UpdateUsersHandler::class),
    CoversClass(UsersUpdateDto::class),
    CoversClass(UsersRole::class),
    CoversClass(UsersUsername::class),
    CoversClass(UsersPassword::class),
    CoversClass(UsersRoleType::class),
    CoversClass(UsersUsernameType::class),
    CoversClass(UsersPasswordType::class),
]
class UpdateUsersHandlerTest extends HephFunctionalTestCase
{
    use InteractsWithMessenger;

    private EntityManagerInterface $entityManager;
    private UsersRepository $repository;
    private UpdateUsersHandler $handler;

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

    /**
     * @throws Exception
     */
    public function testDoctrineConfiguration(): void
    {
        $connection = self::getEntityManager()->getConnection();
        self::assertTrue($connection->isConnected(), 'La connexion à la base de données est inactive');
    }

    /**
     * @throws Exception
     */
    public function testUpdateUsers(): void
    {
        $users = UsersFaker::new();
        $this->entityManager->persist($users);
        $this->entityManager->flush();

        $firstUsers = $this->repository->findOneBy([]);
        self::assertNotNull($firstUsers, 'Entity non trouvée en bdd.');
        self::assertEquals('username', $firstUsers->username());
        self::assertEquals('password', $firstUsers->password());
        self::assertEquals('ROLE_ADMIN', $firstUsers->role());

        $bus = self::getContainer()->get('messenger.default_bus');
        $dto = UsersUpdateDtoFaker::new();
        $command = new UpdateUsersCommand($dto, (string) $firstUsers->id());
        $bus->dispatch($command);
        $this->flush();

        $this->transport('async')->queue()->assertNotEmpty();
        $this->transport('async')->queue()->assertCount(1);
        $this->transport('async')->process(1);
        $this->transport('async')->queue()->assertCount(0);

        $updatedUsers = $this->repository->findOneBy([]);
        self::assertNotNull($updatedUsers, 'Entity non trouvée en bdd.');
        self::assertEquals($dto->username, $updatedUsers->username()->value(), 'Le username ne correspond pas au dto.');
    }
}
