<?php

declare(strict_types=1);

namespace Heph\Fixtures\Ping;

use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Heph\Fixtures\AbstractFixture;
use Heph\Tests\Faker\Entity\Ping\PingFaker;

final class PingFixture extends AbstractFixture implements FixtureGroupInterface
{
    protected function supply(ObjectManager $manager): iterable
    {
        yield PingFaker::new();
    }

    public static function getGroups(): array
    {
        return ['ping'];
    }
}
