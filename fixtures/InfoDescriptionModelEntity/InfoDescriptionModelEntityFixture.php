<?php

declare(strict_types=1);

namespace Heph\Fixtures\InfoDescriptionModelEntity;

use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Heph\Fixtures\AbstractFixture;
use Heph\Tests\Faker\Entity\InfoDescriptionModel\InfoDescriptionModelEntityFaker;

final class InfoDescriptionModelEntityFixture extends AbstractFixture implements FixtureGroupInterface
{
    protected function supply(ObjectManager $manager): iterable
    {
        yield InfoDescriptionModelEntityFaker::new();
    }

    public static function getGroups(): array
    {
        return ['InfoDescriptionModel_entity'];
    }
}
