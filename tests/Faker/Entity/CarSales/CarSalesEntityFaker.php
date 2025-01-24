<?php

declare(strict_types=1);

namespace Heph\Tests\Faker\Entity\CarSales;

use Heph\Entity\CarSales\CarSalesEntity;
use Heph\Tests\Faker\Entity\InfoDescriptionModel\InfoDescriptionModelEntityFaker;

final class CarSalesEntityFaker
{
    public static function new(): CarSalesEntity
    {
        $infoDescriptionModel = InfoDescriptionModelEntityFaker::new();

        return new CarSalesEntity($infoDescriptionModel);
    }
}
