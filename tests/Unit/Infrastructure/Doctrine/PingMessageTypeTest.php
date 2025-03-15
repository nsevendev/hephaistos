<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Infrastructure\Doctrine;

use Doctrine\DBAL\Platforms\MySQLPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use Heph\Entity\Ping\ValueObject\PingMessage;
use Heph\Infrastructure\ApiResponse\Exception\Custom\AbstractApiResponseException;
use Heph\Infrastructure\ApiResponse\Exception\Custom\Ping\PingInvalidArgumentException;
use Heph\Infrastructure\ApiResponse\Exception\Error\Error;
use Heph\Infrastructure\Doctrine\Types\Ping\PingMessageType;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[
    CoversClass(PingMessageType::class),
    CoversClass(PingMessage::class),
    CoversClass(AbstractApiResponseException::class),
    CoversClass(PingInvalidArgumentException::class),
    CoversClass(Error::class),
]
final class PingMessageTypeTest extends HephUnitTestCase
{
    private PingMessageType $type;
    private MySQLPlatform $platform;

    protected function setUp(): void
    {
        if (!Type::hasType('app_ping_message')) {
            Type::addType('app_ping_message', PingMessageType::class);
        }

        $this->type = Type::getType('app_ping_message');
        $this->platform = new MySQLPlatform();
    }

    public function testGetName(): void
    {
        self::assertSame('app_ping_message', $this->type->getName());
    }

    public function testGetSQLDeclaration(): void
    {
        $column = ['length' => 255];
        $sql = $this->type->getSQLDeclaration($column, $this->platform);
        self::assertSame('VARCHAR(255)', $sql);
    }

    /**
     * @throws PingInvalidArgumentException
     * @throws ConversionException
     */
    public function testConvertToPHPValueWithValidString(): void
    {
        $pingMessage = $this->type->convertToPHPValue('Hello, World!', $this->platform);
        self::assertInstanceOf(PingMessage::class, $pingMessage);
        self::assertSame('Hello, World!', $pingMessage->value());
    }

    /**
     * @throws PingInvalidArgumentException
     * @throws ConversionException
     */
    public function testConvertToPHPValueWithNull(): void
    {
        $pingMessage = $this->type->convertToPHPValue(null, $this->platform);
        self::assertNull($pingMessage);
    }

    /**
     * @throws ConversionException
     */
    public function testConvertToPHPValueWithInvalidType(): void
    {
        $this->expectException(PingInvalidArgumentException::class);
        $this->type->convertToPHPValue(123, $this->platform);
    }

    /**
     * @throws PingInvalidArgumentException
     * @throws ConversionException
     */
    public function testConvertToDatabaseValueWithValidPingMessage(): void
    {
        $pingMessage = PingMessage::fromValue('Hello, Database!');
        $dbValue = $this->type->convertToDatabaseValue($pingMessage, $this->platform);
        self::assertSame('Hello, Database!', $dbValue);
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
