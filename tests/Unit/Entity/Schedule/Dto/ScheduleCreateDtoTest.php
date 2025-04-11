<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Entity\Schedule\Dto;

use Heph\Entity\Schedule\Dto\ScheduleCreateDto;
use Heph\Tests\Faker\Dto\Schedule\ScheduleCreateDtoFaker;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[
    CoversClass(ScheduleCreateDto::class),
]
class ScheduleCreateDtoTest extends HephUnitTestCase
{
    public function testScheduleCreateDtoFromFaker(): void
    {
        $scheduleCreateDto = ScheduleCreateDtoFaker::new();

        self::assertNotNull($scheduleCreateDto);

        self::assertInstanceOf(ScheduleCreateDto::class, $scheduleCreateDto);

        self::assertSame('Monday', $scheduleCreateDto->day);
        self::assertSame('08:00', $scheduleCreateDto->hoursOpenAm);
        self::assertSame('12:00', $scheduleCreateDto->hoursCloseAm);
        self::assertSame('13:00', $scheduleCreateDto->hoursOpenPm);
        self::assertSame('18:00', $scheduleCreateDto->hoursClosePm);

        self::assertSame('Monday', (string) $scheduleCreateDto->day);
        self::assertSame('08:00', (string) $scheduleCreateDto->hoursOpenAm);
        self::assertSame('12:00', (string) $scheduleCreateDto->hoursCloseAm);
        self::assertSame('13:00', (string) $scheduleCreateDto->hoursOpenPm);
        self::assertSame('18:00', (string) $scheduleCreateDto->hoursClosePm);
    }

    public function testScheduleCreateDtoWithStaticConstructor(): void
    {
        $scheduleCreateDto = ScheduleCreateDto::new(
            'Tuesday',
            '09:00',
            '11:00',
            '14:00',
            '17:00'
        );

        self::assertNotNull($scheduleCreateDto);

        self::assertInstanceOf(ScheduleCreateDto::class, $scheduleCreateDto);

        self::assertSame('Tuesday', $scheduleCreateDto->day);
        self::assertSame('09:00', $scheduleCreateDto->hoursOpenAm);
        self::assertSame('11:00', $scheduleCreateDto->hoursCloseAm);
        self::assertSame('14:00', $scheduleCreateDto->hoursOpenPm);
        self::assertSame('17:00', $scheduleCreateDto->hoursClosePm);

        self::assertSame('Tuesday', (string) $scheduleCreateDto->day);
        self::assertSame('09:00', (string) $scheduleCreateDto->hoursOpenAm);
        self::assertSame('11:00', (string) $scheduleCreateDto->hoursCloseAm);
        self::assertSame('14:00', (string) $scheduleCreateDto->hoursOpenPm);
        self::assertSame('17:00', (string) $scheduleCreateDto->hoursClosePm);
    }
}
