<?php

declare(strict_types=1);

namespace Heph\Fixtures\WorkShop;

use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Heph\Fixtures\AbstractFixture;
use Heph\Tests\Faker\Entity\WorkShop\WorkShopFaker;

final class WorkShopFixture extends AbstractFixture implements FixtureGroupInterface
{
    protected function supply(ObjectManager $manager): iterable
    {
        yield WorkShopFaker::new();
    }

    public static function getGroups(): array
    {
        return ['engine_remap'];
    }
}
