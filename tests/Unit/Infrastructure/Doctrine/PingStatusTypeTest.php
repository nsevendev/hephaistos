<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Infrastructure\Doctrine;

use Doctrine\DBAL\Platforms\MySQLPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use Heph\Entity\Ping\ValueObject\PingStatus;
use Heph\Infrastructure\ApiResponse\Exception\Custom\AbstractApiResponseException;
use Heph\Infrastructure\ApiResponse\Exception\Custom\Ping\PingInvalidArgumentException;
use Heph\Infrastructure\ApiResponse\Exception\Error\Error;
use Heph\Infrastructure\Doctrine\Types\Ping\PingStatusType;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[
    CoversClass(PingStatusType::class),
    CoversClass(PingStatus::class),
    CoversClass(AbstractApiResponseException::class),
    CoversClass(PingInvalidArgumentException::class),
    CoversClass(Error::class),
]
final class PingStatusTypeTest extends HephUnitTestCase
{
    private PingStatusType $type;
    private MySQLPlatform $platform;

    protected function setUp(): void
    {
        if (!Type::hasType('app_ping_status')) {
            Type::addType('app_ping_status', PingStatusType::class);
        }

        $this->type = Type::getType('app_ping_status');
        $this->platform = new MySQLPlatform();
    }

    public function testGetName(): void
    {
        self::assertSame('app_ping_status', $this->type->getName());
    }

    public function testGetSQLDeclaration(): void
    {
        $column = [];
        $sql = $this->type->getSQLDeclaration($column, $this->platform);
        self::assertSame('INT', $sql);
    }

    /**
     * @throws PingInvalidArgumentException
     * @throws ConversionException
     */
    public function testConvertToPHPValueWithValidString(): void
    {
        $pingStatus = $this->type->convertToPHPValue(200, $this->platform);
        self::assertInstanceOf(PingStatus::class, $pingStatus);
        self::assertSame(200, $pingStatus->value());
    }

    /**
     * @throws PingInvalidArgumentException
     * @throws ConversionException
     */
    public function testConvertToPHPValueWithNull(): void
    {
        $pingStatus = $this->type->convertToPHPValue(null, $this->platform);
        self::assertNull($pingStatus);
    }

    /**
     * @throws PingInvalidArgumentException
     * @throws ConversionException
     */
    public function testConvertToPHPValueWithInvalidType(): void
    {
        $this->expectException(PingInvalidArgumentException::class);
        $this->type->convertToPHPValue('123', $this->platform);
    }

    /**
     * @throws PingInvalidArgumentException
     * @throws ConversionException
     */
    public function testConvertToDatabaseValueWithValidPingMessage(): void
    {
        $pingStatus = PingStatus::fromValue(200);
        $dbValue = $this->type->convertToDatabaseValue($pingStatus, $this->platform);
        self::assertSame(200, $dbValue);
    }

    /**
     * @throws PingInvalidArgumentException
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
        $this->expectException(PingInvalidArgumentException::class);
        $this->type->convertToDatabaseValue('Invalid Type', $this->platform);
    }

    public function testRequiresSQLCommentHint(): void
    {
        self::assertTrue($this->type->requiresSQLCommentHint($this->platform));
    }
}
