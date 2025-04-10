<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Infrastructure\Doctrine;

use Doctrine\DBAL\Platforms\MySQLPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use Heph\Entity\LmQuatre\ValueObject\LmQuatreOwner;
use Heph\Infrastructure\ApiResponse\Exception\Custom\AbstractApiResponseException;
use Heph\Infrastructure\ApiResponse\Exception\Custom\LmQuatre\LmQuatreInvalidArgumentException;
use Heph\Infrastructure\ApiResponse\Exception\Error\Error;
use Heph\Infrastructure\Doctrine\Types\LmQuatre\LmQuatreOwnerType;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[
    CoversClass(LmQuatreOwnerType::class),
    CoversClass(LmQuatreOwner::class),
    CoversClass(AbstractApiResponseException::class),
    CoversClass(LmQuatreInvalidArgumentException::class),
    CoversClass(Error::class),
]
final class LmQuatreOwnerTypeTest extends HephUnitTestCase
{
    private LmQuatreOwnerType $type;
    private MySQLPlatform $platform;

    protected function setUp(): void
    {
        if (!Type::hasType('app_lm_quatre_owner')) {
            Type::addType('app_lm_quatre_owner', LmQuatreOwnerType::class);
        }

        $this->type = Type::getType('app_lm_quatre_owner');
        $this->platform = new MySQLPlatform();
    }

    public function testGetName(): void
    {
        self::assertSame('app_lm_quatre_owner', $this->type->getName());
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
        $lmQuatreOwner = $this->type->convertToPHPValue('Hello, World!', $this->platform);
        self::assertInstanceOf(LmQuatreOwner::class, $lmQuatreOwner);
        self::assertSame('Hello, World!', $lmQuatreOwner->value());
    }

    /**
     * @throws LmQuatreInvalidArgumentException
     * @throws ConversionException
     */
    public function testConvertToPHPValueWithNull(): void
    {
        $lmQuatreOwner = $this->type->convertToPHPValue(null, $this->platform);
        self::assertNull($lmQuatreOwner);
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
    public function testConvertToDatabaseValueWithValidLmQuatreOwner(): void
    {
        $lmQuatreOwner = LmQuatreOwner::fromValue('Hello, Database!');
        $dbValue = $this->type->convertToDatabaseValue($lmQuatreOwner, $this->platform);
        self::assertSame('Hello, Database!', $dbValue);
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
