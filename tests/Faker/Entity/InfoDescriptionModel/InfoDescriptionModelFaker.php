<?php

declare(strict_types=1);

namespace Heph\Tests\Faker\Entity\InfoDescriptionModel;

use Heph\Entity\InfoDescriptionModel\InfoDescriptionModel;
use Heph\Entity\Shared\ValueObject\DescriptionValueObject;
use Heph\Entity\Shared\ValueObject\LibelleValueObject;

final class InfoDescriptionModelFaker
{
    public static function new(): InfoDescriptionModel
    {
        return new InfoDescriptionModel(
            libelle: LibelleValueObject::fromValue('libelle test'),
            description: DescriptionValueObject::fromValue('description test')
        );
    }
}
