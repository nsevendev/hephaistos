<?php

declare(strict_types=1);

namespace Heph\Tests\Faker\Dto\InfoDescriptionModel;

use Heph\Entity\InfoDescriptionModel\Dto\InfoDescriptionModelCreateDto;
use Heph\Entity\Shared\ValueObject\LibelleValueObject;
use Heph\Entity\Shared\ValueObject\DescriptionValueObject;

class InfoDescriptionModelCreateDtoFaker
{
    public static function new(): InfoDescriptionModelCreateDto
    {
        return new InfoDescriptionModelCreateDto(
            id: '1234',
            libelle: LibelleValueObject::fromValue('Libelle test'),
            description: DescriptionValueObject::fromValue('Description test'),
            createdAt: new \DateTimeImmutable('2000-03-31 12:00:00'),
            updatedAt: new \DateTimeImmutable('2000-03-31 13:00:00')
        );
    }
}