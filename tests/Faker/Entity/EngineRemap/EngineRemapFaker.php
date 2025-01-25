<?php

declare(strict_types=1);

namespace Heph\Tests\Faker\Entity\EngineRemap;

use Heph\Entity\EngineRemap\EngineRemap;
use Heph\Tests\Faker\Entity\InfoDescriptionModel\InfoDescriptionModelFaker;

final class EngineRemapFaker
{
    public static function new(): EngineRemap
    {
        $infoDescriptionModel = InfoDescriptionModelFaker::new();

        return new EngineRemap($infoDescriptionModel);
    }
}
