<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Infrastructure\Doctrine;

use Doctrine\DBAL\Platforms\MySQLPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use Heph\Entity\LmQuatre\ValueObject\LmQuatreEmail;
use Heph\Infrastructure\ApiResponse\Exception\Custom\AbstractApiResponseException;
use Heph\Infrastructure\ApiResponse\Exception\Custom\LmQuatre\LmQuatreInvalidArgumentException;
use Heph\Infrastructure\ApiResponse\Exception\Error\Error;
use Heph\Infrastructure\Doctrine\Types\LmQuatre\LmQuatreEmailType;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[
    CoversClass(LmQuatreEmailType::class),
    CoversClass(LmQuatreEmail::class),
    CoversClass(AbstractApiResponseException::class),
    CoversClass(LmQuatreInvalidArgumentException::class),
    CoversClass(Error::class),
]
final class LmQuatreEmailTypeTest extends HephUnitTestCase
{
    private LmQuatreEmailType $type;
    private MySQLPlatform $platform;

    protected function setUp(): void
    {
        if (!Type::hasType('app_lm_quatre_email')) {
            Type::addType('app_lm_quatre_email', LmQuatreEmailType::class);
        }

        $this->type = Type::getType('app_lm_quatre_email');
        $this->platform = new MySQLPlatform();
    }

    public function testGetName(): void
    {
        self::assertSame('app_lm_quatre_email', $this->type->getName());
    }

    public function testGetSQLDeclaration(): void
    {
        $column = ['length' => 50];
        $sql = $this->type->getSQLDeclaration($column, $this->platform);
        self::assertSame('VARCHAR(50)', $sql);
    }

    /**
     * @throws LmQuatreInvalidArgumentException
     * @throws ConversionException
     */
    public function testConvertToPHPValueWithValidString(): void
    {
        $lmQuatreEmail = $this->type->convertToPHPValue('test@test.com', $this->platform);
        self::assertInstanceOf(LmQuatreEmail::class, $lmQuatreEmail);
        self::assertSame('test@test.com', $lmQuatreEmail->value());
    }

    /**
     * @throws LmQuatreInvalidArgumentException
     * @throws ConversionException
     */
    public function testConvertToPHPValueWithNull(): void
    {
        $lmQuatreEmail = $this->type->convertToPHPValue(null, $this->platform);
        self::assertNull($lmQuatreEmail);
    }

    /**
     * @throws ConversionException
     */
    public function testConvertToPHPValueWithInvalidType(): void
    {
        $this->expectException(LmQuatreInvalidArgumentException::class);
        $this->type->convertToPHPValue(123, $this->platform);
    }

    /**
     * @throws LmQuatreInvalidArgumentException
     * @throws ConversionException
     */
    public function testConvertToDatabaseValueWithValidLmQuatreEmail(): void
    {
        $lmQuatreEmail = LmQuatreEmail::fromValue('test@test.com');
        $dbValue = $this->type->convertToDatabaseValue($lmQuatreEmail, $this->platform);
        self::assertSame('test@test.com', $dbValue);
    }

    /**
     * @throws LmQuatreInvalidArgumentException
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
        $this->expectException(LmQuatreInvalidArgumentException::class);
        $this->type->convertToDatabaseValue('Invalid Type', $this->platform);
    }

    public function testRequiresSQLCommentHint(): void
    {
        self::assertTrue($this->type->requiresSQLCommentHint($this->platform));
    }
}
