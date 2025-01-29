<?php

declare(strict_types=1);

namespace Heph\Tests\Faker\Entity\WorkShop;

use Heph\Entity\WorkShop\WorkShop;
use Heph\Tests\Faker\Entity\InfoDescriptionModel\InfoDescriptionModelFaker;

final class WorkShopFaker
{
    public static function new(): WorkShop
    {
        $infoDescriptionModel = InfoDescriptionModelFaker::new();

        return new WorkShop($infoDescriptionModel);
    }
}
