<?php

declare(strict_types=1);

namespace Heph\Tests\Faker\Entity\LmQuatre;

use DateTimeImmutable;
use Heph\Entity\LmQuatre\LmQuatreEntity;
use Heph\Tests\Faker\Entity\InfoDescriptionModel\InfoDescriptionModelEntityFaker;

final class LmQuatreEntityFaker
{
    public static function new(): LmQuatreEntity
    {
        $infoDescriptionModel = InfoDescriptionModelEntityFaker::new();

        return new LmQuatreEntity(
            $infoDescriptionModel,
            'Math',
            '33 rue du test',
            'test@exemple.com',
            123456789,
            new DateTimeImmutable('2000-03-31')
        );
    }

    public static function newWithEmptyValues(): LmQuatreEntity
    {
        $infoDescriptionModel = InfoDescriptionModelEntityFaker::newWithNEmptyValues();

        return new LmQuatreEntity(
            $infoDescriptionModel,
            '',
            "",
            '',
            0,
            new DateTimeImmutable('2000-03-31')
        );
    }
}
