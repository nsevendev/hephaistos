<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Entity\Users;

use DateTimeImmutable;
use Heph\Entity\InfoDescriptionModel\InfoDescriptionModel;
use Heph\Entity\Shared\ValueObject\DescriptionValueObject;
use Heph\Entity\Shared\ValueObject\LibelleValueObject;
use Heph\Entity\Users\Users;
use Heph\Entity\Users\ValueObject\UsersPassword;
use Heph\Entity\Users\ValueObject\UsersRole;
use Heph\Entity\Users\ValueObject\UsersUsername;
use Heph\Infrastructure\ApiResponse\Exception\Custom\Users\UsersInvalidArgumentException;
use Heph\Infrastructure\ApiResponse\Exception\Error\Error;
use Heph\Tests\Faker\Entity\Users\UsersFaker;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[
    CoversClass(Users::class),
    CoversClass(InfoDescriptionModel::class),
    CoversClass(LibelleValueObject::class),
    CoversClass(DescriptionValueObject::class),
    CoversClass(UsersUsername::class),
    CoversClass(UsersPassword::class),
    CoversClass(UsersRole::class),
    CoversClass(UsersInvalidArgumentException::class),
    CoversClass(Error::class),
]
class UsersTest extends HephUnitTestCase
{
    public function testEntityInitialization(): void
    {
        $username = 'username';
        $password = 'password';
        $role = 'admin';

        $Users = UsersFaker::new();

        self::assertInstanceOf(Users::class, $Users);
        self::assertNotNull($Users->id());
        self::assertInstanceOf(DateTimeImmutable::class, $Users->createdAt());
        self::assertInstanceOf(DateTimeImmutable::class, $Users->updatedAt());
        self::assertSame($username, $Users->username()->value());
        self::assertSame($password, $Users->password()->value());
        self::assertSame($role, $Users->role()->value());
        self::assertSame($username, $Users->username()->jsonSerialize());
        self::assertSame($password, $Users->password()->jsonSerialize());
        self::assertSame($role, $Users->role()->jsonSerialize());
        self::assertSame($username, (string) $Users->username());
        self::assertSame($password, (string) $Users->password());
        self::assertSame($role, (string) $Users->role());
    }

    /**
     * @throws UsersInvalidArgumentException
     */
    public function testEntitySetters(): void
    {
        $Users = UsersFaker::new();

        $newDateUpdated = new DateTimeImmutable();
        $Users->setUpdatedAt($newDateUpdated);

        self::assertSame($newDateUpdated, $Users->updatedAt());

        $newRoleUpdate = 'employee';
        $Users->setRole(UsersRole::fromValue($newRoleUpdate));

        self::assertSame($newRoleUpdate, $Users->role()->value());

        $newUsernameUpdate = 'username updated';
        $Users->setUsername(UsersUsername::fromValue($newUsernameUpdate));

        self::assertSame($newUsernameUpdate, $Users->username()->value());

        $newPasswordUpdate = 'password updated';
        $Users->setPassword(UsersPassword::fromValue($newPasswordUpdate));

        self::assertSame($newPasswordUpdate, $Users->password()->value());
    }

    /**
     * @throws UsersInvalidArgumentException
     */
    public function testEntityWithRoleInvalid(): void
    {
        $this->expectException(UsersInvalidArgumentException::class);

        $lmQuatre = UsersFaker::withRoleInvalid();
    }

    /**
     * @throws UsersInvalidArgumentException
     */
    public function testEntityWithRoleEmpty(): void
    {
        $this->expectException(UsersInvalidArgumentException::class);

        $lmQuatre = UsersFaker::withRoleEmpty();
    }

    /**
     * @throws UsersInvalidArgumentException
     */
    public function testEntityWithUsernameMoreLonger(): void
    {
        $this->expectException(UsersInvalidArgumentException::class);

        $lmQuatre = UsersFaker::withUsernameMoreLonger();
    }

    /**
     * @throws UsersInvalidArgumentException
     */
    public function testEntityWithUsernameEmpty(): void
    {
        $this->expectException(UsersInvalidArgumentException::class);

        $lmQuatre = UsersFaker::withUsernameEmpty();
    }

    /**
     * @throws UsersInvalidArgumentException
     */
    public function testEntityWithPasswordMoreLonger(): void
    {
        $this->expectException(UsersInvalidArgumentException::class);

        $lmQuatre = UsersFaker::withPasswordMoreLonger();
    }

    /**
     * @throws UsersInvalidArgumentException
     */
    public function testEntityWithPasswordEmpty(): void
    {
        $this->expectException(UsersInvalidArgumentException::class);

        $lmQuatre = UsersFaker::withPasswordEmpty();
    }
}
