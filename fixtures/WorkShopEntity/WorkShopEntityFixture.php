<?php

declare(strict_types=1);

namespace Heph\Fixtures\WorkShopEntity;

use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Heph\Fixtures\AbstractFixture;
use Heph\Tests\Faker\Entity\WorkShop\WorkShopEntityFaker;

final class WorkShopEntityFixture extends AbstractFixture implements FixtureGroupInterface
{
    protected function supply(ObjectManager $manager): iterable
    {
        yield WorkShopEntityFaker::new();
    }

    public static function getGroups(): array
    {
        return ['engine_remap_entity'];
    }
}
