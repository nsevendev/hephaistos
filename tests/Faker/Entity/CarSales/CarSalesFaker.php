<?php

declare(strict_types=1);

namespace Heph\Tests\Faker\Entity\CarSales;

use Heph\Entity\CarSales\CarSales;
use Heph\Tests\Faker\Entity\InfoDescriptionModel\InfoDescriptionModelFaker;

final class CarSalesFaker
{
    public static function new(): CarSales
    {
        $infoDescriptionModel = InfoDescriptionModelFaker::new();

        return new CarSales($infoDescriptionModel);
    }
}
