<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Entity\Users\Dto;

use Heph\Entity\Users\Dto\UsersCreateDto;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(UsersCreateDto::class)]
class UsersCreateDtoTest extends HephUnitTestCase
{
    public function testUsersCreateDto(): void
    {
        $lmQuatreDto = new UsersCreateDto('username', 'password', 'ROLE_ADMIN');

        self::assertNotNull($lmQuatreDto);
        self::assertInstanceOf(UsersCreateDto::class, $lmQuatreDto);
    }

    public function testUsersCreateDtoWithFunctionNew(): void
    {
        $lmQuatreDto = UsersCreateDto::new('username', 'password', 'ROLE_ADMIN');

        self::assertNotNull($lmQuatreDto);
        self::assertInstanceOf(UsersCreateDto::class, $lmQuatreDto);
    }
}
