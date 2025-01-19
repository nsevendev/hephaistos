<?php

declare(strict_types=1);

namespace Heph\Fixtures\PingEntity;

use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Heph\Entity\Ping\PingEntity;
use Heph\Fixtures\AbstractFixture;

final class PingEntityFixture extends AbstractFixture implements FixtureGroupInterface
{
    protected function supply(ObjectManager $manager): iterable
    {
        yield new PingEntity(
            200,
            "ping OK"
        );
    }

    public static function getGroups(): array
    {
        return ['ping_entity'];
    }
}
