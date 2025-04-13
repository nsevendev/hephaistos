<?php

declare(strict_types=1);

namespace Heph\Tests\Faker\Dto\WorkShop;

use Heph\Entity\WorkShop\Dto\WorkShopUpdateDto;

class WorkShopUpdateDtoFaker
{
    public static function new(): WorkShopUpdateDto
    {
        return WorkShopUpdateDto::new('libelle update', 'description update');
    }
}
