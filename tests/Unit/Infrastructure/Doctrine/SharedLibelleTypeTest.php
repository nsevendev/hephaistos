<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Infrastructure\Doctrine;

use Doctrine\DBAL\Platforms\MySQLPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use Heph\Entity\Shared\ValueObject\LibelleValueObject;
use Heph\Infrastructure\ApiResponse\Exception\Custom\AbstractApiResponseException;
use Heph\Infrastructure\ApiResponse\Exception\Custom\Shared\GenericException;
use Heph\Infrastructure\ApiResponse\Exception\Error\Error;
use Heph\Infrastructure\Doctrine\Types\Shared\LibelleType;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[
    CoversClass(LibelleType::class),
    CoversClass(LibelleValueObject::class),
    CoversClass(AbstractApiResponseException::class),
    CoversClass(GenericException::class),
    CoversClass(Error::class),
]
final class SharedLibelleTypeTest extends HephUnitTestCase
{
    private LibelleType $type;
    private MySQLPlatform $platform;

    protected function setUp(): void
    {
        if (!Type::hasType('app_shared_libelle')) {
            Type::addType('app_shared_libelle', LibelleType::class);
        }

        $this->type = Type::getType('app_shared_libelle');
        $this->platform = new MySQLPlatform();
    }

    public function testGetName(): void
    {
        self::assertSame('app_shared_libelle', $this->type->getName());
    }

    public function testGetSQLDeclaration(): void
    {
        $column = ['length' => 75];
        $sql = $this->type->getSQLDeclaration($column, $this->platform);
        self::assertSame('VARCHAR(75)', $sql);
    }

    /**
     * @throws GenericException
     * @throws ConversionException
     */
    public function testConvertToPHPValueWithValidString(): void
    {
        $libelleValueObject = $this->type->convertToPHPValue('Hello, World!', $this->platform);
        self::assertInstanceOf(LibelleValueObject::class, $libelleValueObject);
        self::assertSame('Hello, World!', $libelleValueObject->value());
    }

    /**
     * @throws GenericException
     * @throws ConversionException
     */
    public function testConvertToPHPValueWithNull(): void
    {
        $libelleValueObject = $this->type->convertToPHPValue(null, $this->platform);
        self::assertNull($libelleValueObject);
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
    public function testConvertToDatabaseValueWithValidLibelleValueObject(): void
    {
        $libelleValueObject = LibelleValueObject::fromValue('Hello, Database!');
        $dbValue = $this->type->convertToDatabaseValue($libelleValueObject, $this->platform);
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
