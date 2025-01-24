<?php

declare(strict_types=1);

namespace Heph\Fixtures\EngineRemapEntity;

use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Heph\Fixtures\AbstractFixture;
use Heph\Tests\Faker\Entity\EngineRemap\EngineRemapEntityFaker;

final class EngineRemapEntityFixture extends AbstractFixture implements FixtureGroupInterface
{
    protected function supply(ObjectManager $manager): iterable
    {
        yield EngineRemapEntityFaker::new();
    }

    public static function getGroups(): array
    {
        return ['engine_remap_entity'];
    }
}
