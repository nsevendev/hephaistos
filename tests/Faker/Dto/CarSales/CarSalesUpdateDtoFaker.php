<?php

declare(strict_types=1);

namespace Heph\Tests\Faker\Dto\CarSales;

use Heph\Entity\CarSales\Dto\CarSalesUpdateDto;

class CarSalesUpdateDtoFaker
{
    public static function new(): CarSalesUpdateDto
    {
        return CarSalesUpdateDto::new('libelle update', 'description update');
    }
}
