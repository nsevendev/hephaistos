<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Infrastructure\Doctrine;

use Doctrine\DBAL\Platforms\MySQLPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use Heph\Entity\Schedule\ValueObject\ScheduleHoursClosePm;
use Heph\Infrastructure\ApiResponse\Exception\Custom\AbstractApiResponseException;
use Heph\Infrastructure\ApiResponse\Exception\Custom\Schedule\ScheduleInvalidArgumentException;
use Heph\Infrastructure\ApiResponse\Exception\Error\Error;
use Heph\Infrastructure\Doctrine\Types\Schedule\ScheduleHoursClosePmType;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[
    CoversClass(ScheduleHoursClosePmType::class),
    CoversClass(ScheduleHoursClosePm::class),
    CoversClass(AbstractApiResponseException::class),
    CoversClass(ScheduleInvalidArgumentException::class),
    CoversClass(Error::class),
]
final class ScheduleHoursClosePmTypeTest extends HephUnitTestCase
{
    private ScheduleHoursClosePmType $type;
    private MySQLPlatform $platform;

    protected function setUp(): void
    {
        if (!Type::hasType('app_schedule_hours_close_pm')) {
            Type::addType('app_schedule_hours_close_pm', ScheduleHoursClosePmType::class);
        }

        $this->type = Type::getType('app_schedule_hours_close_pm');
        $this->platform = new MySQLPlatform();
    }

    public function testGetName(): void
    {
        self::assertSame('app_schedule_hours_close_pm', $this->type->getName());
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
        $scheduleHoursClosePm = $this->type->convertToPHPValue('Hello, World!', $this->platform);
        self::assertInstanceOf(ScheduleHoursClosePm::class, $scheduleHoursClosePm);
        self::assertSame('Hello, World!', $scheduleHoursClosePm->value());
    }

    /**
     * @throws ScheduleInvalidArgumentException
     * @throws ConversionException
     */
    public function testConvertToPHPValueWithNull(): void
    {
        $scheduleHoursClosePm = $this->type->convertToPHPValue(null, $this->platform);
        self::assertNull($scheduleHoursClosePm);
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
    public function testConvertToDatabaseValueWithValidScheduleHoursClosePm(): void
    {
        $scheduleHoursClosePm = ScheduleHoursClosePm::fromValue('Hello, Database!');
        $dbValue = $this->type->convertToDatabaseValue($scheduleHoursClosePm, $this->platform);
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
