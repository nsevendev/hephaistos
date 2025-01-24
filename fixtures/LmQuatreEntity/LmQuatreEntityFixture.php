<?php

declare(strict_types=1);

namespace Heph\Fixtures\LmQuatreEntity;

use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Heph\Fixtures\AbstractFixture;
use Heph\Tests\Faker\Entity\LmQuatre\LmQuatreEntityFaker;

final class LmQuatreEntityFixture extends AbstractFixture implements FixtureGroupInterface
{
    protected function supply(ObjectManager $manager): iterable
    {
        yield LmQuatreEntityFaker::new();
    }

    public static function getGroups(): array
    {
        return ['lm_quatre_entity'];
    }
}
