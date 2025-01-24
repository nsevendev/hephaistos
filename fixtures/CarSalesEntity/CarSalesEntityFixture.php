<?php

declare(strict_types=1);

namespace Heph\Fixtures\CarSalesEntity;

use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Heph\Fixtures\AbstractFixture;
use Heph\Tests\Faker\Entity\CarSales\CarSalesEntityFaker;

final class CarSalesEntityFixture extends AbstractFixture implements FixtureGroupInterface
{
    protected function supply(ObjectManager $manager): iterable
    {
        yield CarSalesEntityFaker::new();
    }

    public static function getGroups(): array
    {
        return ['car_sales_entity'];
    }
}
