<?php

declare(strict_types=1);

namespace Heph\Tests\Faker\Dto\WorkShop;

use Heph\Entity\InfoDescriptionModel\Dto\InfoDescriptionModelCreateDto;
use Heph\Entity\WorkShop\Dto\WorkShopCreateDto;

class WorkShopCreateDtoFaker
{
    public static function new(): WorkShopCreateDto
    {
        return new WorkShopCreateDto(
            infoDescriptionModel: InfoDescriptionModelCreateDto::new('libelle test', 'description test'),
        );
    }
}
