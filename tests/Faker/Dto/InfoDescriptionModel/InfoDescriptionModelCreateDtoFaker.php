<?php

declare(strict_types=1);

namespace Heph\Tests\Faker\Dto\InfoDescriptionModel;

use Heph\Entity\InfoDescriptionModel\Dto\InfoDescriptionModelCreateDto;
use Heph\Entity\Shared\ValueObject\LibelleValueObject;
use Heph\Entity\Shared\ValueObject\DescriptionValueObject;

class InfoDescriptionModelCreateDtoFaker
{
    public static function new(): InfoDescriptionModelCreateDto
    {
        return new InfoDescriptionModelCreateDto(
            LibelleValueObject::fromValue('Libelle test'),
            DescriptionValueObject::fromValue('Description test')
        );
    }
}
