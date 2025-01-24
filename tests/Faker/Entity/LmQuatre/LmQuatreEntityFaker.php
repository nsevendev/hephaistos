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
        info_description_model: $infoDescriptionModel = InfoDescriptionModelEntityFaker::new();

        return new LmQuatreEntity(
            infoDescriptionModel: $infoDescriptionModel,
            owner: 'Math',
            adresse: '33 rue du test',
            email: 'test@exemple.com',
            phoneNumber: 123456789,
            companyCreateDate: new DateTimeImmutable('2000-03-31')
        );
    }

    public static function newWithEmptyValues(): LmQuatreEntity
    {
        $infoDescriptionModel = InfoDescriptionModelEntityFaker::newWithNEmptyValues();

        return new LmQuatreEntity(
            infoDescriptionModel: $infoDescriptionModel,
            owner: '',
            adresse: '',
            email: '',
            phoneNumber: 0,
            companyCreateDate: new DateTimeImmutable('2000-03-31')
        );
    }
}
