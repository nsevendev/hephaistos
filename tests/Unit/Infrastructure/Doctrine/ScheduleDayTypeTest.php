<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Infrastructure\Doctrine;

use Doctrine\DBAL\Platforms\MySQLPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use Heph\Entity\Schedule\ValueObject\ScheduleDay;
use Heph\Infrastructure\ApiResponse\Exception\Custom\AbstractApiResponseException;
use Heph\Infrastructure\ApiResponse\Exception\Custom\Schedule\ScheduleInvalidArgumentException;
use Heph\Infrastructure\ApiResponse\Exception\Error\Error;
use Heph\Infrastructure\Doctrine\Types\Schedule\ScheduleDayType;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[
    CoversClass(ScheduleDayType::class),
    CoversClass(ScheduleDay::class),
    CoversClass(AbstractApiResponseException::class),
    CoversClass(ScheduleInvalidArgumentException::class),
    CoversClass(Error::class),
]
final class ScheduleDayTypeTest extends HephUnitTestCase
{
    private ScheduleDayType $type;
    private MySQLPlatform $platform;

    protected function setUp(): void
    {
        if (!Type::hasType('app_schedule_day')) {
            Type::addType('app_schedule_day', ScheduleDayType::class);
        }

        $this->type = Type::getType('app_schedule_day');
        $this->platform = new MySQLPlatform();
    }

    public function testGetName(): void
    {
        self::assertSame('app_schedule_day', $this->type->getName());
    }

    public function testGetSQLDeclaration(): void
    {
        $column = ['length' => 255];
        $sql = $this->type->getSQLDeclaration($column, $this->platform);
        self::assertSame('VARCHAR(255)', $sql);
    }

    /**
     * @throws ScheduleInvalidArgumentException
     * @throws ConversionException
     */
    public function testConvertToPHPValueWithValidString(): void
    {
        $scheduleDay = $this->type->convertToPHPValue('Hello, World!', $this->platform);
        self::assertInstanceOf(ScheduleDay::class, $scheduleDay);
        self::assertSame('Hello, World!', $scheduleDay->value());
    }

    /**
     * @throws ScheduleInvalidArgumentException
     * @throws ConversionException
     */
    public function testConvertToPHPValueWithNull(): void
    {
        $scheduleDay = $this->type->convertToPHPValue(null, $this->platform);
        self::assertNull($scheduleDay);
    }

    /**
     * @throws ConversionException
     */
    public function testConvertToPHPValueWithInvalidType(): void
    {
        $this->expectException(ScheduleInvalidArgumentException::class);
        $this->type->convertToPHPValue(123, $this->platform);
    }

    /**
     * @throws ScheduleInvalidArgumentException
     * @throws ConversionException
     */
    public function testConvertToDatabaseValueWithValidScheduleDay(): void
    {
        $scheduleDay = ScheduleDay::fromValue('Hello, Database!');
        $dbValue = $this->type->convertToDatabaseValue($scheduleDay, $this->platform);
        self::assertSame('Hello, Database!', $dbValue);
    }

    /**
     * @throws ScheduleInvalidArgumentException
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
        $this->expectException(ScheduleInvalidArgumentException::class);
        $this->type->convertToDatabaseValue('Invalid Type', $this->platform);
    }

    public function testRequiresSQLCommentHint(): void
    {
        self::assertTrue($this->type->requiresSQLCommentHint($this->platform));
    }
}
