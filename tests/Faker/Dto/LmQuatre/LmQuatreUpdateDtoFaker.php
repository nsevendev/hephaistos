<?php

declare(strict_types=1);

namespace Heph\Tests\Faker\Dto\LmQuatre;

use DateTimeImmutable;
use Heph\Entity\InfoDescriptionModel\Dto\InfoDescriptionModelCreateDto;
use Heph\Entity\LmQuatre\Dto\LmQuatreUpdateDto;

class LmQuatreUpdateDtoFaker
{
    public static function new(): LmQuatreUpdateDto
    {
        return new LmQuatreUpdateDto(
            InfoDescriptionModelCreateDto::new('libelle test', 'description test'),
            'owner updated',
            'adresse faker',
            'test@test.com',
            '123456789',
            new DateTimeImmutable('2000-03-31')
        );
    }
}
