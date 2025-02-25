<?php

declare(strict_types=1);

namespace Heph\Tests\Faker\Dto\InfoDescriptionModel;

use Heph\Entity\InfoDescriptionModel\Dto\InfoDescriptionModelCreateDto;
use Heph\Entity\Shared\ValueObject\DescriptionValueObject;
use Heph\Entity\Shared\ValueObject\LibelleValueObject;

class InfoDescriptionModelCreateDtoFaker
{
    public static function new(): InfoDescriptionModelCreateDto
    {
        return new InfoDescriptionModelCreateDto(
            libelle: LibelleValueObject::fromValue('Libelle test'),
            description: DescriptionValueObject::fromValue('Description test'),
        );
    }
}
