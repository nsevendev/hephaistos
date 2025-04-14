<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Infrastructure\Doctrine;

use Doctrine\DBAL\Platforms\MySQLPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use Heph\Entity\Users\ValueObject\UsersRole;
use Heph\Infrastructure\ApiResponse\Exception\Custom\AbstractApiResponseException;
use Heph\Infrastructure\ApiResponse\Exception\Custom\Users\UsersInvalidArgumentException;
use Heph\Infrastructure\ApiResponse\Exception\Error\Error;
use Heph\Infrastructure\Doctrine\Types\Users\UsersRoleType;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[
    CoversClass(UsersRoleType::class),
    CoversClass(UsersRole::class),
    CoversClass(AbstractApiResponseException::class),
    CoversClass(UsersInvalidArgumentException::class),
    CoversClass(Error::class),
]
final class UsersRoleTypeTest extends HephUnitTestCase
{
    private UsersRoleType $type;
    private MySQLPlatform $platform;

    protected function setUp(): void
    {
        if (!Type::hasType('app_users_role')) {
            Type::addType('app_users_role', UsersRoleType::class);
        }

        $this->type = Type::getType('app_users_role');
        $this->platform = new MySQLPlatform();
    }

    public function testGetName(): void
    {
        self::assertSame('app_users_role', $this->type->getName());
    }

    public function testGetSQLDeclaration(): void
    {
        $column = ['length' => 255];
        $sql = $this->type->getSQLDeclaration($column, $this->platform);
        self::assertSame('VARCHAR(255)', $sql);
    }

    /**
     * @throws UsersInvalidArgumentException
     * @throws ConversionException
     */
    public function testConvertToPHPValueWithValidString(): void
    {
        $usersRole = $this->type->convertToPHPValue('admin', $this->platform);
        self::assertInstanceOf(UsersRole::class, $usersRole);
        self::assertSame('admin', $usersRole->value());
    }

    /**
     * @throws UsersInvalidArgumentException
     * @throws ConversionException
     */
    public function testConvertToPHPValueWithNull(): void
    {
        $usersRole = $this->type->convertToPHPValue(null, $this->platform);
        self::assertNull($usersRole);
    }

    /**
     * @throws ConversionException
     */
    public function testConvertToPHPValueWithInvalidType(): void
    {
        $this->expectException(UsersInvalidArgumentException::class);
        $this->type->convertToPHPValue(123, $this->platform);
    }

    /**
     * @throws UsersInvalidArgumentException
     * @throws ConversionException
     */
    public function testConvertToDatabaseValueWithValidUsersRole(): void
    {
        $usersRole = UsersRole::fromValue('admin');
        $dbValue = $this->type->convertToDatabaseValue($usersRole, $this->platform);
        self::assertSame('admin', $dbValue);
    }

    /**
     * @throws UsersInvalidArgumentException
     * @throws ConversionException
     */
    public function testConvertToDatabaseValueWithNull(): void
    {
        $dbValue = $this->type->convertToDatabaseValue(null, $this->platform);
        self::assertNull($dbValue);
    }

    /**
     * @throws ConversionException
     */
    public function testConvertToDatabaseValueWithInvalidType(): void
    {
        $this->expectException(UsersInvalidArgumentException::class);
        $this->type->convertToDatabaseValue('Invalid Type', $this->platform);
    }

    public function testRequiresSQLCommentHint(): void
    {
        self::assertTrue($this->type->requiresSQLCommentHint($this->platform));
    }
}
