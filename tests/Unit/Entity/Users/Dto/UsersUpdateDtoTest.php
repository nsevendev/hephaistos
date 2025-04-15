<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Entity\Users\Dto;

use Heph\Entity\Users\Dto\UsersUpdateDto;
use Heph\Tests\Faker\Dto\Users\UsersUpdateDtoFaker;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(UsersUpdateDto::class)]
class UsersUpdateDtoTest extends HephUnitTestCase
{
    public function testUsersUpdateDto(): void
    {
        $updateUsersDto = new UsersUpdateDto('username', 'password', 'ROLE_ADMIN');

        self::assertNotNull($updateUsersDto);

        self::assertInstanceOf(UsersUpdateDto::class, $updateUsersDto);

        self::assertSame('username', $updateUsersDto->username);
        self::assertSame('password', $updateUsersDto->password);
        self::assertSame('ROLE_ADMIN', $updateUsersDto->role);

        self::assertSame('username', (string) $updateUsersDto->username);
        self::assertSame('password', (string) $updateUsersDto->password);
        self::assertSame('ROLE_ADMIN', (string) $updateUsersDto->role);
    }

    public function testUsersUpdateDtoWithFaker(): void
    {
        $updateUsersDto = UsersUpdateDtoFaker::new();

        self::assertNotNull($updateUsersDto);
        self::assertInstanceOf(UsersUpdateDto::class, $updateUsersDto);

        self::assertSame('username', $updateUsersDto->username);
        self::assertSame('password', $updateUsersDto->password);
        self::assertSame('ROLE_ADMIN', $updateUsersDto->role);

        self::assertSame('username', (string) $updateUsersDto->username);
        self::assertSame('password', (string) $updateUsersDto->password);
        self::assertSame('ROLE_ADMIN', (string) $updateUsersDto->role);
    }

    public function testUsersUpdateDtoWithFonctionNew(): void
    {
        $updateUsersDto = UsersUpdateDto::new('username', 'password', 'ROLE_ADMIN');

        self::assertNotNull($updateUsersDto);
        self::assertInstanceOf(UsersUpdateDto::class, $updateUsersDto);

        self::assertSame('username', $updateUsersDto->username);
        self::assertSame('password', $updateUsersDto->password);
        self::assertSame('ROLE_ADMIN', $updateUsersDto->role);

        self::assertSame('username', (string) $updateUsersDto->username);
        self::assertSame('password', (string) $updateUsersDto->password);
        self::assertSame('ROLE_ADMIN', (string) $updateUsersDto->role);
    }
}
