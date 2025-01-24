<?php

declare(strict_types=1);

namespace Heph\Tests\Faker\Entity\WorkShop;

use Heph\Entity\WorkShop\WorkShopEntity;
use Heph\Tests\Faker\Entity\InfoDescriptionModel\InfoDescriptionModelEntityFaker;

final class WorkShopEntityFaker
{
    public static function new(): WorkShopEntity
    {
        info_description_model : $infoDescriptionModel = InfoDescriptionModelEntityFaker::new();

        return new WorkShopEntity($infoDescriptionModel);
    }
}
