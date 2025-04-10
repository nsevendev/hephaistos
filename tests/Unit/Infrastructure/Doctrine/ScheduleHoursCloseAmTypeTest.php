<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Infrastructure\Doctrine;

use Doctrine\DBAL\Platforms\MySQLPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use Heph\Entity\Schedule\ValueObject\ScheduleHoursCloseAm;
use Heph\Infrastructure\ApiResponse\Exception\Custom\AbstractApiResponseException;
use Heph\Infrastructure\ApiResponse\Exception\Custom\Schedule\ScheduleInvalidArgumentException;
use Heph\Infrastructure\ApiResponse\Exception\Error\Error;
use Heph\Infrastructure\Doctrine\Types\Schedule\ScheduleHoursCloseAmType;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[
    CoversClass(ScheduleHoursCloseAmType::class),
    CoversClass(ScheduleHoursCloseAm::class),
    CoversClass(AbstractApiResponseException::class),
    CoversClass(ScheduleInvalidArgumentException::class),
    CoversClass(Error::class),
]
final class ScheduleHoursCloseAmTypeTest extends HephUnitTestCase
{
    private ScheduleHoursCloseAmType $type;
    private MySQLPlatform $platform;

    protected function setUp(): void
    {
        if (!Type::hasType('app_schedule_hours_close_am')) {
            Type::addType('app_schedule_hours_close_am', ScheduleHoursCloseAmType::class);
        }

        $this->type = Type::getType('app_schedule_hours_close_am');
        $this->platform = new MySQLPlatform();
    }

    public function testGetName(): void
    {
        self::assertSame('app_schedule_hours_close_am', $this->type->getName());
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
        $scheduleHoursCloseAm = $this->type->convertToPHPValue('Hello, World!', $this->platform);
        self::assertInstanceOf(ScheduleHoursCloseAm::class, $scheduleHoursCloseAm);
        self::assertSame('Hello, World!', $scheduleHoursCloseAm->value());
    }

    /**
     * @throws ScheduleInvalidArgumentException
     * @throws ConversionException
     */
    public function testConvertToPHPValueWithNull(): void
    {
        $scheduleHoursCloseAm = $this->type->convertToPHPValue(null, $this->platform);
        self::assertNull($scheduleHoursCloseAm);
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
    public function testConvertToDatabaseValueWithValidScheduleHoursCloseAm(): void
    {
        $scheduleHoursCloseAm = ScheduleHoursCloseAm::fromValue('Hello, Database!');
        $dbValue = $this->type->convertToDatabaseValue($scheduleHoursCloseAm, $this->platform);
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
