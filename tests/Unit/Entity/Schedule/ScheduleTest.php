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
use Heph\Infrastructure\ApiResponse\Exception\Custom\Schedule\ScheduleInvalidArgumentException;
use Heph\Tests\Faker\Entity\Schedule\ScheduleFaker;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Heph\Infrastructure\ApiResponse\Exception\Error\Error;
use Heph\Infrastructure\ApiResponse\Exception\Custom\AbstractApiResponseException;

#[
    CoversClass(Schedule::class),
    CoversClass(ScheduleDay::class),
    CoversClass(ScheduleHoursCloseAm::class),
    CoversClass(ScheduleHoursClosePm::class),
    CoversClass(ScheduleHoursOpenAm::class),
    CoversClass(ScheduleHoursOpenPm::class),
    CoversClass(AbstractApiResponseException::class),
    CoversClass(Error::class),
    CoversClass(ScheduleInvalidArgumentException::class),
]
class ScheduleTest extends HephUnitTestCase
{
    /**
     * @throws ScheduleInvalidArgumentException
     */
    public function testEntityInitialization(): void
    {
        $schedule = ScheduleFaker::new();
        $day = 'Monday';
        $hoursOpenAm = '09:00';
        $hoursCloseAm = '12:00';
        $hoursOpenPm = '13:00';
        $hoursClosePm = '17:00';

        self::assertInstanceOf(Schedule::class, $schedule);
        self::assertNotNull($schedule->id());
        self::assertSame($day, $schedule->day()->value());
        self::assertSame($hoursOpenAm, $schedule->hoursOpenAm()->value());
        self::assertSame($hoursCloseAm, $schedule->hoursCloseAm()->value());
        self::assertSame($hoursOpenPm, $schedule->hoursOpenPm()->value());
        self::assertSame($hoursClosePm, $schedule->hoursClosePm()->value());
        self::assertSame($day, $schedule->day()->jsonSerialize());
        self::assertSame($hoursOpenAm, $schedule->hoursOpenAm()->jsonSerialize());
        self::assertSame($hoursCloseAm, $schedule->hoursCloseAm()->jsonSerialize());
        self::assertSame($hoursOpenPm, $schedule->hoursOpenPm()->jsonSerialize());
        self::assertSame($hoursClosePm, $schedule->hoursClosePm()->jsonSerialize());
        self::assertSame($day, (string) $schedule->day());
        self::assertSame($hoursOpenAm, (string) $schedule->hoursOpenAm());
        self::assertSame($hoursCloseAm, (string) $schedule->hoursCloseAm());
        self::assertSame($hoursOpenPm, (string) $schedule->hoursOpenPm());
        self::assertSame($hoursClosePm, (string) $schedule->hoursClosePm());
        self::assertInstanceOf(DateTimeImmutable::class, $schedule->createdAt());
        self::assertInstanceOf(DateTimeImmutable::class, $schedule->updatedAt());
    }

    /**
     * @throws ScheduleInvalidArgumentException
     */
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

    /**
     * @throws ScheduleInvalidArgumentException
     */
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

    public function testEntityWithDayEmpty(): void
    {
        $this->expectException(ScheduleInvalidArgumentException::class);

        $schedule = ScheduleFaker::withDayEmpty();
    }

    public function testEntityWithHoursOpenAmEmpty(): void
    {
        $this->expectException(ScheduleInvalidArgumentException::class);

        $schedule = ScheduleFaker::withHoursOpenAmEmpty();
    }

    public function testEntityWithHoursCloseAmEmpty(): void
    {
        $this->expectException(ScheduleInvalidArgumentException::class);

        $schedule = ScheduleFaker::withHoursCloseAmEmpty();
    }

    public function testEntityWithHoursOpenPmEmpty(): void
    {
        $this->expectException(ScheduleInvalidArgumentException::class);

        $schedule = ScheduleFaker::withHoursOpenPmEmpty();
    }

    public function testEntityWithHoursClosePmEmpty(): void
    {
        $this->expectException(ScheduleInvalidArgumentException::class);

        $schedule = ScheduleFaker::withHoursClosePmEmpty();
    }
}
