<?php

declare(strict_types=1);

namespace Heph\Tests\Faker\Dto\EngineRemap;

use DateTimeImmutable;
use Heph\Entity\EngineRemap\Dto\EngineRemapCreateDto;
use Heph\Entity\InfoDescriptionModel\InfoDescriptionModel;
use Symfony\Component\Uid\Uuid;

class EngineRemapCreateDtoFaker
{
    public static function new(): EngineRemapCreateDto
    {
        return new EngineRemapCreateDto(
            id: Uuid::v7(),
            infoDescriptionModel: new InfoDescriptionModel('libelle test', 'description test'),
            createdAt: new DateTimeImmutable(),
            updatedAt: new DateTimeImmutable(),
        );
    }
}
