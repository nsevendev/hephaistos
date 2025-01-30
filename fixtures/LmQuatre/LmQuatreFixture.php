<?php

declare(strict_types=1);

namespace Heph\Fixtures\LmQuatre;

use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Heph\Fixtures\AbstractFixture;
use Heph\Tests\Faker\Entity\LmQuatre\LmQuatreFaker;

final class LmQuatreFixture extends AbstractFixture implements FixtureGroupInterface
{
    protected function supply(ObjectManager $manager): iterable
    {
        yield LmQuatreFaker::new();
    }

    public static function getGroups(): array
    {
        return ['lm_quatre'];
    }
}
