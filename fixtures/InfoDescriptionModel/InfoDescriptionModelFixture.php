<?php

declare(strict_types=1);

namespace Heph\Fixtures\InfoDescriptionModel;

use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Heph\Fixtures\AbstractFixture;
use Heph\Tests\Faker\Entity\InfoDescriptionModel\InfoDescriptionModelFaker;

final class InfoDescriptionModelFixture extends AbstractFixture implements FixtureGroupInterface
{
    protected function supply(ObjectManager $manager): iterable
    {
        yield InfoDescriptionModelFaker::new();
    }

    public static function getGroups(): array
    {
        return ['info_description_model'];
    }
}
