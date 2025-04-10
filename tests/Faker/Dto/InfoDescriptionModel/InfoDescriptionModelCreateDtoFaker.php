<?php

declare(strict_types=1);

namespace Heph\Tests\Faker\Dto\InfoDescriptionModel;

use Heph\Entity\InfoDescriptionModel\Dto\InfoDescriptionModelCreateDto;

class InfoDescriptionModelCreateDtoFaker
{
    public static function new(): InfoDescriptionModelCreateDto
    {
        return new InfoDescriptionModelCreateDto(
            'Libelle test',
            'Description test',
        );
    }
}
