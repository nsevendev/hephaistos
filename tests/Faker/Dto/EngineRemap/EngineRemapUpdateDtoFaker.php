<?php

declare(strict_types=1);

namespace Heph\Tests\Faker\Dto\EngineRemap;

use Heph\Entity\EngineRemap\Dto\EngineRemapUpdateDto;

class EngineRemapUpdateDtoFaker
{
    public static function new(): EngineRemapUpdateDto
    {
        return EngineRemapUpdateDto::new('libelle update', 'description update');
    }
}
