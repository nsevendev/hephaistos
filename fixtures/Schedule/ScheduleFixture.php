<?php

declare(strict_types=1);

namespace Heph\Fixtures\Schedule;

use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Heph\Fixtures\AbstractFixture;
use Heph\Tests\Faker\Entity\Schedule\ScheduleFaker;

final class ScheduleFixture extends AbstractFixture implements FixtureGroupInterface
{
    protected function supply(ObjectManager $manager): iterable
    {
        yield ScheduleFaker::new();
    }

    public static function getGroups(): array
    {
        return ['schedule'];
    }
}
