<?php

declare(strict_types=1);

namespace Heph\Fixtures\PingEntity;

use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Heph\Fixtures\AbstractFixture;
use Heph\Tests\Faker\Entity\Ping\PingEntityFaker;

final class PingEntityFixture extends AbstractFixture implements FixtureGroupInterface
{
    protected function supply(ObjectManager $manager): iterable
    {
        yield PingEntityFaker::new();
    }

    public static function getGroups(): array
    {
        return ['ping_entity'];
    }
}
