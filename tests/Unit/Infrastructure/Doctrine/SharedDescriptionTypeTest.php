<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Infrastructure\Doctrine;

use Doctrine\DBAL\Platforms\MySQLPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use Heph\Entity\Shared\ValueObject\DescriptionValueObject;
use Heph\Infrastructure\ApiResponse\Exception\Custom\AbstractApiResponseException;
use Heph\Infrastructure\ApiResponse\Exception\Custom\Shared\GenericException;
use Heph\Infrastructure\ApiResponse\Exception\Error\Error;
use Heph\Infrastructure\Doctrine\Types\Shared\DescriptionType;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[
    CoversClass(DescriptionType::class),
    CoversClass(DescriptionValueObject::class),
    CoversClass(AbstractApiResponseException::class),
    CoversClass(GenericException::class),
    CoversClass(Error::class),
]
final class SharedDescriptionTypeTest extends HephUnitTestCase
{
    private DescriptionType $type;
    private MySQLPlatform $platform;

    protected function setUp(): void
    {
        if (!Type::hasType('app_shared_description')) {
            Type::addType('app_shared_description', DescriptionType::class);
        }

        $this->type = Type::getType('app_shared_description');
        $this->platform = new MySQLPlatform();
    }

    public function testGetName(): void
    {
        self::assertSame('app_shared_description', $this->type->getName());
    }

    public function testGetSQLDeclaration(): void
    {
        $column = ['length' => 255];
        $sql = $this->type->getSQLDeclaration($column, $this->platform);
        self::assertSame('VARCHAR(255)', $sql);
    }

    /**
     * @throws GenericException
     * @throws ConversionException
     */
    public function testConvertToPHPValueWithValidString(): void
    {
        $descriptionValueObject = $this->type->convertToPHPValue('Hello, World!', $this->platform);
        self::assertInstanceOf(DescriptionValueObject::class, $descriptionValueObject);
        self::assertSame('Hello, World!', $descriptionValueObject->value());
    }

    /**
     * @throws GenericException
     * @throws ConversionException
     */
    public function testConvertToPHPValueWithNull(): void
    {
        $descriptionValueObject = $this->type->convertToPHPValue(null, $this->platform);
        self::assertNull($descriptionValueObject);
    }

    /**
     * @throws ConversionException
     */
    public function testConvertToPHPValueWithInvalidType(): void
    {
        $this->expectException(GenericException::class);
        $this->type->convertToPHPValue(123, $this->platform);
    }

    /**
     * @throws GenericException
     * @throws ConversionException
     */
    public function testConvertToDatabaseValueWithValidDescriptionValueObject(): void
    {
        $descriptionValueObject = DescriptionValueObject::fromValue('Hello, Database!');
        $dbValue = $this->type->convertToDatabaseValue($descriptionValueObject, $this->platform);
        self::assertSame('Hello, Database!', $dbValue);
    }

    /**
     * @throws GenericException
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
        $this->expectException(GenericException::class);
        $this->type->convertToDatabaseValue('Invalid Type', $this->platform);
    }

    public function testRequiresSQLCommentHint(): void
    {
        self::assertTrue($this->type->requiresSQLCommentHint($this->platform));
    }
}
