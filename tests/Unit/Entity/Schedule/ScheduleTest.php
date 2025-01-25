<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Entity\Schedule;

use DateTimeImmutable;
use Heph\Entity\Schedule\Schedule;
use Heph\Entity\Shared\Type\Uid;
use Heph\Infrastructure\Doctrine\Type\UidType;
use Heph\Tests\Faker\Entity\Schedule\ScheduleFaker;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(Schedule::class), CoversClass(Uid::class), CoversClass(UidType::class)]
class ScheduleTest extends HephUnitTestCase
{
    public function testEntityInitialization(): void
    {
        $schedule = ScheduleFaker::new();

        self::assertInstanceOf(Schedule::class, $schedule);
        self::assertNotNull($schedule->id());
        self::assertSame('Monday', $schedule->day());
        self::assertSame('09:00', $schedule->hoursStart());
        self::assertSame('17:00', $schedule->hoursEnd());
        self::assertInstanceOf(DateTimeImmutable::class, $schedule->createdAt());
        self::assertInstanceOf(DateTimeImmutable::class, $schedule->updatedAt());
    }

    public function testEntitySetters(): void
    {
        $schedule = ScheduleFaker::new();

        $newDateUpdated = new DateTimeImmutable();
        $schedule->setUpdatedAt($newDateUpdated);

        self::assertSame($newDateUpdated, $schedule->updatedAt());

        $newDayUpdate = 'Tuesday';
        $schedule->setDay($newDayUpdate);

        self::assertSame($newDayUpdate, $schedule->day());

        $newHoursStartUpdate = '08:00';
        $schedule->setHoursStart($newHoursStartUpdate);

        self::assertSame($newHoursStartUpdate, $schedule->hoursStart());

        $newHoursEndUpdate = '16:00';
        $schedule->setHoursEnd($newHoursEndUpdate);

        self::assertSame($newHoursEndUpdate, $schedule->hoursEnd());
    }
}
