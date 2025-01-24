<?php

declare(strict_types=1);

namespace Heph\Tests\Faker\Entity\EngineRemap;

use Heph\Entity\EngineRemap\EngineRemapEntity;
use Heph\Tests\Faker\Entity\InfoDescriptionModel\InfoDescriptionModelEntityFaker;

final class EngineRemapEntityFaker
{
    public static function new(): EngineRemapEntity
    {
        $infoDescriptionModel = InfoDescriptionModelEntityFaker::new();
        return new EngineRemapEntity($infoDescriptionModel);
    }
}