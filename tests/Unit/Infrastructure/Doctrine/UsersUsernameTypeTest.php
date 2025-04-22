<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Infrastructure\Doctrine;

use Doctrine\DBAL\Platforms\MySQLPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use Heph\Entity\Users\ValueObject\UsersUsername;
use Heph\Infrastructure\ApiResponse\Exception\Custom\AbstractApiResponseException;
use Heph\Infrastructure\ApiResponse\Exception\Custom\Users\UsersInvalidArgumentException;
use Heph\Infrastructure\ApiResponse\Exception\Error\Error;
use Heph\Infrastructure\Doctrine\Types\Users\UsersUsernameType;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[
    CoversClass(UsersUsernameType::class),
    CoversClass(UsersUsername::class),
    CoversClass(AbstractApiResponseException::class),
    CoversClass(UsersInvalidArgumentException::class),
    CoversClass(Error::class),
]
final class UsersUsernameTypeTest extends HephUnitTestCase
{
    private UsersUsernameType $type;
    private MySQLPlatform $platform;

    protected function setUp(): void
    {
        if (!Type::hasType('app_users_username')) {
            Type::addType('app_users_username', UsersUsernameType::class);
        }

        $this->type = Type::getType('app_users_username');
        $this->platform = new MySQLPlatform();
    }

    public function testGetName(): void
    {
        self::assertSame('app_users_username', $this->type->getName());
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
        $usersUsername = $this->type->convertToPHPValue('Hello, World!', $this->platform);
        self::assertInstanceOf(UsersUsername::class, $usersUsername);
        self::assertSame('Hello, World!', $usersUsername->value());
    }

    /**
     * @throws UsersInvalidArgumentException
     * @throws ConversionException
     */
    public function testConvertToPHPValueWithNull(): void
    {
        $usersUsername = $this->type->convertToPHPValue(null, $this->platform);
        self::assertNull($usersUsername);
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
    public function testConvertToDatabaseValueWithValidUsersUsername(): void
    {
        $usersUsername = UsersUsername::fromValue('Hello, Database!');
        $dbValue = $this->type->convertToDatabaseValue($usersUsername, $this->platform);
        self::assertSame('Hello, Database!', $dbValue);
    }

    /**
     * @throws UsersInvalidArgumentException
     * @throws ConversionException
     */
    public function testConvertToDatabaseValueWithStringValue(): void
    {
        $usersUsername = 'Hello, Database!';
        $dbValue = $this->type->convertToDatabaseValue($usersUsername, $this->platform);
        self::assertSame('Hello, Database!', $dbValue);
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
        $this->type->convertToDatabaseValue(123, $this->platform);
    }

    public function testRequiresSQLCommentHint(): void
    {
        self::assertTrue($this->type->requiresSQLCommentHint($this->platform));
    }
}
