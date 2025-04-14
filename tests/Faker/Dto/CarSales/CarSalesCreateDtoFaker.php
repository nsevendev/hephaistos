<?php

declare(strict_types=1);

namespace Heph\Tests\Faker\Dto\CarSales;

use Heph\Entity\CarSales\Dto\CarSalesCreateDto;
use Heph\Entity\InfoDescriptionModel\Dto\InfoDescriptionModelCreateDto;

class CarSalesCreateDtoFaker
{
    public static function new(): CarSalesCreateDto
    {
        return new CarSalesCreateDto(
            infoDescriptionModel: InfoDescriptionModelCreateDto::new('libelle test', 'description test'),
        );
    }
}
