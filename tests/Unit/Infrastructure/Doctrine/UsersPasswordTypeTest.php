<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Infrastructure\Doctrine;

use Doctrine\DBAL\Platforms\MySQLPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use Heph\Entity\Users\ValueObject\UsersPassword;
use Heph\Infrastructure\ApiResponse\Exception\Custom\AbstractApiResponseException;
use Heph\Infrastructure\ApiResponse\Exception\Custom\Users\UsersInvalidArgumentException;
use Heph\Infrastructure\ApiResponse\Exception\Error\Error;
use Heph\Infrastructure\Doctrine\Types\Users\UsersPasswordType;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[
    CoversClass(UsersPasswordType::class),
    CoversClass(UsersPassword::class),
    CoversClass(AbstractApiResponseException::class),
    CoversClass(UsersInvalidArgumentException::class),
    CoversClass(Error::class),
]
final class UsersPasswordTypeTest extends HephUnitTestCase
{
    private UsersPasswordType $type;
    private MySQLPlatform $platform;

    protected function setUp(): void
    {
        if (!Type::hasType('app_users_password')) {
            Type::addType('app_users_password', UsersPasswordType::class);
        }

        $this->type = Type::getType('app_users_password');
        $this->platform = new MySQLPlatform();
    }

    public function testGetName(): void
    {
        self::assertSame('app_users_password', $this->type->getName());
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
        $usersPassword = $this->type->convertToPHPValue('Hello, World!', $this->platform);
        self::assertInstanceOf(UsersPassword::class, $usersPassword);
        self::assertSame('Hello, World!', $usersPassword->value());
    }

    /**
     * @throws UsersInvalidArgumentException
     * @throws ConversionException
     */
    public function testConvertToPHPValueWithNull(): void
    {
        $usersPassword = $this->type->convertToPHPValue(null, $this->platform);
        self::assertNull($usersPassword);
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
    public function testConvertToDatabaseValueWithValidUsersPassword(): void
    {
        $usersPassword = UsersPassword::fromValue('Hello, Database!');
        $dbValue = $this->type->convertToDatabaseValue($usersPassword, $this->platform);
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
        $this->type->convertToDatabaseValue('Invalid Type', $this->platform);
    }

    public function testRequiresSQLCommentHint(): void
    {
        self::assertTrue($this->type->requiresSQLCommentHint($this->platform));
    }
}
