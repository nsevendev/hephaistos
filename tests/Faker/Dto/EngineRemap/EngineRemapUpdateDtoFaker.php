<?php

declare(strict_types=1);

namespace Heph\Tests\Faker\Dto\EngineRemap;

use Heph\Entity\EngineRemap\Dto\EngineRemapUpdateDto;
use Heph\Entity\Shared\ValueObject\DescriptionValueObject;
use Heph\Entity\Shared\ValueObject\LibelleValueObject;

class EngineRemapUpdateDtoFaker
{
    public static function new(): EngineRemapUpdateDto
    {
        return new EngineRemapUpdateDto(
            libelle: new LibelleValueObject('libelle update'),
            description: new DescriptionValueObject('description update')
        );
    }
}
