<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Entity\Dto;

use Heph\Entity\Schedule\Dto\ScheduleCreateDto;
use Heph\Entity\Shared\ValueObject\ScheduleDay;
use Heph\Entity\Shared\ValueObject\ScheduleHoursEnd;
use Heph\Entity\Shared\ValueObject\ScheduleHoursStart;
use Heph\Tests\Faker\Dto\Schedule\ScheduleCreateDtoFaker;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(ScheduleCreateDto::class), CoversClass(ScheduleDay::class), CoversClass(ScheduleHoursStart::class), CoversClass(ScheduleHoursEnd::class)]
class ScheduleCreateDtoTest extends HephUnitTestCase
{
    public function testScheduleCreateDto(): void
    {
        $scheduleCreateDto = ScheduleCreateDtoFaker::new();

        self::assertNotNull($scheduleCreateDto);

        self::assertInstanceOf(ScheduleCreateDto::class, $scheduleCreateDto);
        self::assertInstanceOf(ScheduleDay::class, $scheduleCreateDto->day());
        self::assertInstanceOf(ScheduleHoursStart::class, $scheduleCreateDto->hours_start());
        self::assertInstanceOf(ScheduleHoursEnd::class, $scheduleCreateDto->hours_end());

        self::assertSame('Monday', $scheduleCreateDto->day()->value());
        self::assertSame('08:00', $scheduleCreateDto->hours_start()->value());
        self::assertSame('18:00', $scheduleCreateDto->hours_end()->value());

        self::assertSame('Monday', (string) $scheduleCreateDto->day());
        self::assertSame('08:00', (string) $scheduleCreateDto->hours_start());
        self::assertSame('18:00', (string) $scheduleCreateDto->hours_end());
    }

    public function testScheduleCreateDtoWithFunctionNew(): void
    {
        $scheduleCreateDto = ScheduleCreateDto::new(
            'Friday',
            '07:00',
            '19:00'
        );

        self::assertNotNull($scheduleCreateDto);

        self::assertInstanceOf(ScheduleCreateDto::class, $scheduleCreateDto);
        self::assertInstanceOf(ScheduleDay::class, $scheduleCreateDto->day());
        self::assertInstanceOf(ScheduleHoursStart::class, $scheduleCreateDto->hours_start());
        self::assertInstanceOf(ScheduleHoursEnd::class, $scheduleCreateDto->hours_end());

        self::assertSame('Friday', $scheduleCreateDto->day()->value());
        self::assertSame('07:00', $scheduleCreateDto->hours_start()->value());
        self::assertSame('19:00', $scheduleCreateDto->hours_end()->value());

        self::assertSame('Friday', (string) $scheduleCreateDto->day());
        self::assertSame('07:00', (string) $scheduleCreateDto->hours_start());
        self::assertSame('19:00', (string) $scheduleCreateDto->hours_end());
    }
}
