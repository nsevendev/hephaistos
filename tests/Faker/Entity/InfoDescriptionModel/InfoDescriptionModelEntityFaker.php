<?php

declare(strict_types=1);

namespace Heph\Tests\Faker\Entity\InfoDescriptionModel;

use Heph\Entity\InfoDescriptionModel\InfoDescriptionModelEntity;

final class InfoDescriptionModelEntityFaker
{
    public static function new(): InfoDescriptionModelEntity
    {
        return new InfoDescriptionModelEntity(
            libelle: 'libellé test',
            description: 'description test'
        );
    }

    public static function newWithNullValues(): InfoDescriptionModelEntity
    {
        return new InfoDescriptionModelEntity(
            libelle: null,
            description: null
        );
    }
}
