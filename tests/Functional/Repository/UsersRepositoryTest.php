<?php

declare(strict_types=1);

namespace Heph\Tests\Functional\Repository;

use Doctrine\DBAL\Exception;
use Heph\Entity\InfoDescriptionModel\InfoDescriptionModel;
use Heph\Entity\Users\Users;
use Heph\Entity\Users\ValueObject\UsersPassword;
use Heph\Entity\Users\ValueObject\UsersRole;
use Heph\Entity\Users\ValueObject\UsersUsername;
use Heph\Infrastructure\Doctrine\Types\Users\UsersPasswordType;
use Heph\Infrastructure\Doctrine\Types\Users\UsersRoleType;
use Heph\Infrastructure\Doctrine\Types\Users\UsersUsernameType;
use Heph\Repository\Users\UsersRepository;
use Heph\Tests\Faker\Entity\Users\UsersFaker;
use Heph\Tests\Functional\HephFunctionalTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use ReflectionException;

#[
    CoversClass(UsersRepository::class),
    CoversClass(Users::class),
    CoversClass(InfoDescriptionModel::class),
    CoversClass(UsersUsername::class),
    CoversClass(UsersUsernameType::class),
    CoversClass(UsersPassword::class),
    CoversClass(UsersPasswordType::class),
    CoversClass(UsersRole::class),
    CoversClass(UsersRoleType::class),
]
class UsersRepositoryTest extends HephFunctionalTestCase
{
    private UsersRepository $usersRepository;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->getConnection()->beginTransaction();

        /** @var UsersRepository $repository */
        $repository = self::getContainer()->get(UsersRepository::class);
        $this->usersRepository = $repository;
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
     * @throws ReflectionException
     */
    public function testWeCanPersistAndFindUsers(): void
    {
        $users = UsersFaker::new();

        $this->persistAndFlush($users);

        /** @var Users|null $found */
        $found = $this->usersRepository->find($users->id());

        self::assertNotNull($found, 'Users non trouvé en base alors qu’on vient de le créer');
        self::assertInstanceOf(Users::class, $found);
        self::assertSame('username', $found->username()->value());
        self::assertSame('password', $found->password()->value());
        self::assertSame('ROLE_ADMIN', $found->role()->value());
    }

    /**
     * @throws ReflectionException
     */
    public function testWeCanUpdateUsers(): void
    {
        $users = UsersFaker::new();

        $this->persistAndFlush($users);

        $users->setUsername(new UsersUsername('new username'));
        $users->setPassword(new UsersPassword('new passwod'));
        $users->setRole(new UsersRole('ROLE_EMPLOYEE'));

        $this->persistAndFlush($users);

        /** @var Users|null $found */
        $found = $this->usersRepository->find($users->id());

        self::assertNotNull($found, 'Users non trouvé en base alors qu’on vient de le modifier');
        self::assertSame('new username', $found->username()->value());
        self::assertSame('new passwod', $found->password()->value());
        self::assertSame('ROLE_EMPLOYEE', $found->role()->value());
    }
}
