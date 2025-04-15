<?php

declare(strict_types=1);

namespace Heph\Tests\Functional\Message\Users;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Heph\Entity\Users\Dto\UsersLoginDto;
use Heph\Entity\Users\Users;
use Heph\Entity\Users\ValueObject\UsersPassword;
use Heph\Entity\Users\ValueObject\UsersRole;
use Heph\Entity\Users\ValueObject\UsersUsername;
use Heph\Infrastructure\Doctrine\Types\Users\UsersPasswordType;
use Heph\Infrastructure\Doctrine\Types\Users\UsersRoleType;
use Heph\Infrastructure\Doctrine\Types\Users\UsersUsernameType;
use Heph\Message\Query\Users\LoginUsersHandler;
use Heph\Message\Query\Users\LoginUsersQuery;
use Heph\Repository\Users\UsersRepository;
use Heph\Tests\Functional\HephFunctionalTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

#[
    CoversClass(UsersRepository::class),
    CoversClass(Users::class),
    CoversClass(UsersLoginDto::class),
    CoversClass(LoginUsersQuery::class),
    CoversClass(LoginUsersHandler::class),
    CoversClass(UsersUsername::class),
    CoversClass(UsersPassword::class),
    CoversClass(UsersRole::class),
    CoversClass(UsersUsernameType::class),
    CoversClass(UsersPasswordType::class),
    CoversClass(UsersRoleType::class),
]
class LoginUsersHandlerTest extends HephFunctionalTestCase
{
    use InteractsWithMessenger;

    private EntityManagerInterface $entityManager;
    private UsersRepository $repository;
    private UserPasswordHasherInterface $hasher;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        self::bootKernel();
        $this->entityManager = self::getEntityManager();
        $this->entityManager->getConnection()->beginTransaction();
        $this->repository = $this->entityManager->getRepository(Users::class);
        $this->hasher = self::getContainer()->get(UserPasswordHasherInterface::class);
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

    public function testHandlerProcessesMessage(): void
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

        $bus = self::getContainer()->get('messenger.default_bus');
        $dto = new UsersLoginDto('username test', 'password test');
        $query = new LoginUsersQuery($dto);

        /** @var string|null $token */
        $token = $bus->dispatch($query)->last(HandledStamp::class)?->getResult();

        self::assertNotNull($token, 'Le token JWT ne doit pas être null');
        self::assertIsString($token, 'Le token doit être une chaîne de caractères');
        self::assertStringStartsWith('eyJ', $token);
    }

    public function testHandlerProcessesMessageWithEmptyBdd(): void
    {
        $bus = self::getContainer()->get('messenger.default_bus');
        $dto = new UsersLoginDto('username test', 'password test');
        $query = new LoginUsersQuery($dto);

        /** @var string|null $token */
        $token = $bus->dispatch($query)->last(HandledStamp::class)?->getResult();

        self::assertNull($token);
    }
}
