<?php

declare(strict_types=1);

namespace Heph\Tests\Faker\Entity\LmQuatre;

use DateTimeImmutable;
use Heph\Entity\LmQuatre\LmQuatre;
use Heph\Tests\Faker\Entity\InfoDescriptionModel\InfoDescriptionModelFaker;

final class LmQuatreFaker
{
    public static function new(): LmQuatre
    {
        $infoDescriptionModel = InfoDescriptionModelFaker::new();

        return new LmQuatre(
            infoDescriptionModel: $infoDescriptionModel,
            owner: 'Math',
            adresse: '33 rue du test',
            email: 'test@exemple.com',
            phoneNumber: '123456789',
            companyCreateDate: new DateTimeImmutable('2000-03-31')
        );
    }
}
