<?php

declare(strict_types=1);

namespace Heph\Tests\Faker\Dto\InfoDescriptionModel;

use DateTimeImmutable;
use Heph\Entity\InfoDescriptionModel\Dto\InfoDescriptionModelCreateDto;
use Heph\Entity\Shared\ValueObject\DescriptionValueObject;
use Heph\Entity\Shared\ValueObject\LibelleValueObject;
use Symfony\Component\Uid\Uuid;

class InfoDescriptionModelCreateDtoFaker
{
    public static function new(): InfoDescriptionModelCreateDto
    {
        return new InfoDescriptionModelCreateDto(
            id: Uuid::v7(),
            libelle: LibelleValueObject::fromValue('Libelle test'),
            description: DescriptionValueObject::fromValue('Description test'),
            createdAt: new DateTimeImmutable('2000-03-31 12:00:00'),
            updatedAt: new DateTimeImmutable('2000-03-31 13:00:00')
        );
    }
}
