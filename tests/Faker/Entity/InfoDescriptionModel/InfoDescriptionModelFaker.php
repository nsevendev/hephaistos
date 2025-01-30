<?php

declare(strict_types=1);

namespace Heph\Tests\Faker\Entity\InfoDescriptionModel;

use Heph\Entity\InfoDescriptionModel\InfoDescriptionModel;

final class InfoDescriptionModelFaker
{
    public static function new(): InfoDescriptionModel
    {
        return new InfoDescriptionModel(
            libelle: 'libellé test',
            description: 'description test'
        );
    }
}
