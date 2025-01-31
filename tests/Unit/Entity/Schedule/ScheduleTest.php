<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Entity\Schedule;

use DateTimeImmutable;
use Heph\Entity\Schedule\Schedule;
use Heph\Tests\Faker\Entity\Schedule\ScheduleFaker;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(Schedule::class)]
class ScheduleTest extends HephUnitTestCase
{
    public function testEntityInitialization(): void
    {
        $schedule = ScheduleFaker::new();

        self::assertInstanceOf(Schedule::class, $schedule);
        self::assertNotNull($schedule->id());
        self::assertSame('Monday', $schedule->day());
        self::assertSame('09:00', $schedule->hoursOpenAm());
        self::assertSame('12:00', $schedule->hoursCloseAm());
        self::assertSame('13:00', $schedule->hoursOpenPm());
        self::assertSame('17:00', $schedule->hoursClosePm());
        self::assertInstanceOf(DateTimeImmutable::class, $schedule->createdAt());
        self::assertInstanceOf(DateTimeImmutable::class, $schedule->updatedAt());
    }

    public function testEntitySetters(): void
    {
        $schedule = ScheduleFaker::new();

        $newDateUpdated = new DateTimeImmutable();
        $schedule->setUpdatedAt($newDateUpdated);
        self::assertSame($newDateUpdated, $schedule->updatedAt());

        $newDay = 'Tuesday';
        $schedule->setDay($newDay);
        self::assertSame($newDay, $schedule->day());

        $newHoursOpenAm = '08:30';
        $schedule->setHoursOpenAm($newHoursOpenAm);
        self::assertSame($newHoursOpenAm, $schedule->hoursOpenAm());

        $newHoursCloseAm = '11:30';
        $schedule->setHoursCloseAm($newHoursCloseAm);
        self::assertSame($newHoursCloseAm, $schedule->hoursCloseAm());

        $newHoursOpenPm = '14:00';
        $schedule->setHoursOpenPm($newHoursOpenPm);
        self::assertSame($newHoursOpenPm, $schedule->hoursOpenPm());

        $newHoursClosePm = '18:00';
        $schedule->setHoursClosePm($newHoursClosePm);
        self::assertSame($newHoursClosePm, $schedule->hoursClosePm());
    }

    public function testAutomaticUpdatedAtChange(): void
    {
        $schedule = ScheduleFaker::new();
        $initialUpdatedAt = $schedule->updatedAt();

        $schedule->setDay('Wednesday');
        self::assertNotSame($initialUpdatedAt, $schedule->updatedAt());

        $schedule->setHoursOpenAm('08:00');
        self::assertNotSame($initialUpdatedAt, $schedule->updatedAt());

        $schedule->setHoursOpenPm('14:30');
        self::assertNotSame($initialUpdatedAt, $schedule->updatedAt());
    }
}
