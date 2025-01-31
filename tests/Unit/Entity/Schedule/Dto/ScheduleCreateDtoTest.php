<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Entity\Schedule\Dto;

use Heph\Entity\Schedule\Dto\ScheduleCreateDto;
use Heph\Entity\Shared\ValueObject\DayValueObject;
use Heph\Entity\Shared\ValueObject\HoursCloseAmValueObject;
use Heph\Entity\Shared\ValueObject\HoursClosePmValueObject;
use Heph\Entity\Shared\ValueObject\HoursOpenAmValueObject;
use Heph\Entity\Shared\ValueObject\HoursOpenPmValueObject;
use Heph\Tests\Faker\Dto\Schedule\ScheduleCreateDtoFaker;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[
    CoversClass(ScheduleCreateDto::class),
    CoversClass(DayValueObject::class),
    CoversClass(HoursOpenAmValueObject::class),
    CoversClass(HoursCloseAmValueObject::class),
    CoversClass(HoursOpenPmValueObject::class),
    CoversClass(HoursClosePmValueObject::class)
]
class ScheduleCreateDtoTest extends HephUnitTestCase
{
    public function testScheduleCreateDtoFromFaker(): void
    {
        $scheduleCreateDto = ScheduleCreateDtoFaker::new();

        self::assertNotNull($scheduleCreateDto);

        self::assertInstanceOf(ScheduleCreateDto::class, $scheduleCreateDto);
        self::assertInstanceOf(DayValueObject::class, $scheduleCreateDto->day());
        self::assertInstanceOf(HoursOpenAmValueObject::class, $scheduleCreateDto->hoursOpenAm());
        self::assertInstanceOf(HoursCloseAmValueObject::class, $scheduleCreateDto->hoursCloseAm());
        self::assertInstanceOf(HoursOpenPmValueObject::class, $scheduleCreateDto->hoursOpenPm());
        self::assertInstanceOf(HoursClosePmValueObject::class, $scheduleCreateDto->hoursClosePm());

        self::assertSame('Monday', $scheduleCreateDto->day()->value());
        self::assertSame('08:00', $scheduleCreateDto->hoursOpenAm()->value());
        self::assertSame('12:00', $scheduleCreateDto->hoursCloseAm()->value());
        self::assertSame('13:00', $scheduleCreateDto->hoursOpenPm()->value());
        self::assertSame('18:00', $scheduleCreateDto->hoursClosePm()->value());

        self::assertSame('Monday', (string) $scheduleCreateDto->day());
        self::assertSame('08:00', (string) $scheduleCreateDto->hoursOpenAm());
        self::assertSame('12:00', (string) $scheduleCreateDto->hoursCloseAm());
        self::assertSame('13:00', (string) $scheduleCreateDto->hoursOpenPm());
        self::assertSame('18:00', (string) $scheduleCreateDto->hoursClosePm());
    }

    public function testScheduleCreateDtoWithStaticConstructor(): void
    {
        $scheduleCreateDto = ScheduleCreateDto::new(
            day: 'Tuesday',
            hoursOpenAm: '09:00',
            hoursCloseAm: '11:00',
            hoursOpenPm: '14:00',
            hoursClosePm: '17:00'
        );

        self::assertNotNull($scheduleCreateDto);

        self::assertInstanceOf(ScheduleCreateDto::class, $scheduleCreateDto);
        self::assertInstanceOf(DayValueObject::class, $scheduleCreateDto->day());
        self::assertInstanceOf(HoursOpenAmValueObject::class, $scheduleCreateDto->hoursOpenAm());
        self::assertInstanceOf(HoursCloseAmValueObject::class, $scheduleCreateDto->hoursCloseAm());
        self::assertInstanceOf(HoursOpenPmValueObject::class, $scheduleCreateDto->hoursOpenPm());
        self::assertInstanceOf(HoursClosePmValueObject::class, $scheduleCreateDto->hoursClosePm());

        self::assertSame('Tuesday', $scheduleCreateDto->day()->value());
        self::assertSame('09:00', $scheduleCreateDto->hoursOpenAm()->value());
        self::assertSame('11:00', $scheduleCreateDto->hoursCloseAm()->value());
        self::assertSame('14:00', $scheduleCreateDto->hoursOpenPm()->value());
        self::assertSame('17:00', $scheduleCreateDto->hoursClosePm()->value());

        self::assertSame('Tuesday', (string) $scheduleCreateDto->day());
        self::assertSame('09:00', (string) $scheduleCreateDto->hoursOpenAm());
        self::assertSame('11:00', (string) $scheduleCreateDto->hoursCloseAm());
        self::assertSame('14:00', (string) $scheduleCreateDto->hoursOpenPm());
        self::assertSame('17:00', (string) $scheduleCreateDto->hoursClosePm());
    }
}
