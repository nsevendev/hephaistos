<?php

declare(strict_types=1);

namespace Heph\Tests\Faker\Dto\EngineRemap;

use Heph\Entity\EngineRemap\Dto\EngineRemapCreateDto;
use Heph\Entity\InfoDescriptionModel\Dto\InfoDescriptionModelCreateDto;

class EngineRemapCreateDtoFaker
{
    public static function new(): EngineRemapCreateDto
    {
        return new EngineRemapCreateDto(
            infoDescriptionModel: InfoDescriptionModelCreateDto::new('libelle test', 'description test'),
        );
    }
}
