<?php

declare(strict_types=1);

namespace Heph\Fixtures\CarSales;

use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Heph\Fixtures\AbstractFixture;
use Heph\Tests\Faker\Entity\CarSales\CarSalesFaker;

final class CarSalesFixture extends AbstractFixture implements FixtureGroupInterface
{
    protected function supply(ObjectManager $manager): iterable
    {
        yield CarSalesFaker::new();
    }

    public static function getGroups(): array
    {
        return ['car_sales'];
    }
}
