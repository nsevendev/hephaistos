<?php

declare(strict_types=1);

namespace Heph\Fixtures\EngineRemap;

use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Heph\Fixtures\AbstractFixture;
use Heph\Tests\Faker\Entity\EngineRemap\EngineRemapFaker;

final class EngineRemapFixture extends AbstractFixture implements FixtureGroupInterface
{
    protected function supply(ObjectManager $manager): iterable
    {
        yield EngineRemapFaker::new();
    }

    public static function getGroups(): array
    {
        return ['engine_remap'];
    }
}
