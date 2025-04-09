<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Entity\Schedule;

use DateTimeImmutable;
use Heph\Entity\Schedule\Schedule;
use Heph\Entity\Schedule\ValueObject\ScheduleDay;
use Heph\Entity\Schedule\ValueObject\ScheduleHoursCloseAm;
use Heph\Entity\Schedule\ValueObject\ScheduleHoursClosePm;
use Heph\Entity\Schedule\ValueObject\ScheduleHoursOpenAm;
use Heph\Entity\Schedule\ValueObject\ScheduleHoursOpenPm;
use Heph\Tests\Faker\Entity\Schedule\ScheduleFaker;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[
    CoversClass(Schedule::class),
    CoversClass(ScheduleDay::class),
    CoversClass(ScheduleHoursCloseAm::class),
    CoversClass(ScheduleHoursClosePm::class),
    CoversClass(ScheduleHoursOpenAm::class),
    CoversClass(ScheduleHoursOpenPm::class),
]
class ScheduleTest extends HephUnitTestCase
{
    public function testEntityInitialization(): void
    {
        $schedule = ScheduleFaker::new();

        self::assertInstanceOf(Schedule::class, $schedule);
        self::assertNotNull($schedule->id());
        self::assertSame('Monday', $schedule->day()->value());
        self::assertSame('09:00', $schedule->hoursOpenAm()->value());
        self::assertSame('12:00', $schedule->hoursCloseAm()->value());
        self::assertSame('13:00', $schedule->hoursOpenPm()->value());
        self::assertSame('17:00', $schedule->hoursClosePm()->value());
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
        $schedule->setDay(new ScheduleDay($newDay));
        self::assertSame($newDay, $schedule->day()->value());

        $newHoursOpenAm = '08:30';
        $schedule->setHoursOpenAm(new ScheduleHoursOpenAm($newHoursOpenAm));
        self::assertSame($newHoursOpenAm, $schedule->hoursOpenAm()->value());

        $newHoursCloseAm = '11:30';
        $schedule->setHoursCloseAm(new ScheduleHoursCloseAm($newHoursCloseAm));
        self::assertSame($newHoursCloseAm, $schedule->hoursCloseAm()->value());

        $newHoursOpenPm = '14:00';
        $schedule->setHoursOpenPm(new ScheduleHoursOpenPm($newHoursOpenPm));
        self::assertSame($newHoursOpenPm, $schedule->hoursOpenPm()->value());

        $newHoursClosePm = '18:00';
        $schedule->setHoursClosePm(new ScheduleHoursClosePm($newHoursClosePm));
        self::assertSame($newHoursClosePm, $schedule->hoursClosePm()->value());
    }

    public function testAutomaticUpdatedAtChange(): void
    {
        $schedule = ScheduleFaker::new();
        $initialUpdatedAt = $schedule->updatedAt();

        $schedule->setDay(new ScheduleDay('Wednesday'));
        self::assertNotSame($initialUpdatedAt, $schedule->updatedAt());

        $schedule->setHoursOpenAm(new ScheduleHoursOpenAm('08:00'));
        self::assertNotSame($initialUpdatedAt, $schedule->updatedAt());

        $schedule->setHoursOpenPm(new ScheduleHoursOpenPm('14:30'));
        self::assertNotSame($initialUpdatedAt, $schedule->updatedAt());
    }
}
