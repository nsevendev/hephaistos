<?php

declare(strict_types=1);

namespace Heph\Tests\Faker\Dto\LmQuatre;

use DateTimeImmutable;
use Heph\Entity\InfoDescriptionModel\Dto\InfoDescriptionModelCreateDto;
use Heph\Entity\LmQuatre\Dto\LmQuatreCreateDto;

class LmQuatreCreateDtoFaker
{
    public static function new(): LmQuatreCreateDto
    {
        return new LmQuatreCreateDto(
            InfoDescriptionModelCreateDto::new('libelle test', 'description test'),
            'Math',
            'adresse faker',
            'test@test.com',
            '123456789',
            new DateTimeImmutable('2000-03-31')
        );
    }
}
