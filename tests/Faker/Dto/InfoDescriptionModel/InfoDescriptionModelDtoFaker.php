<?php

declare(strict_types=1);

namespace Heph\Tests\Faker\Dto\InfoDescriptionModel;

use Heph\Entity\InfoDescriptionModel\Dto\InfoDescriptionModelDto;
use Symfony\Component\Uid\Uuid;

class InfoDescriptionModelDtoFaker
{
    public static function new(): InfoDescriptionModelDto
    {
        return new InfoDescriptionModelDto(
            id: Uuid::v7()->toRfc4122(),
            libelle: 'Libelle test',
            description: 'Description test',
            createdAt: '2000-03-31 12:00:00',
            updatedAt: '2000-03-31 13:00:00'
        );
    }

    /**
     * @return InfoDescriptionModelDto[]
     */
    public static function collection(int $count = 3): array
    {
        $dtos = [];

        for ($i = 0; $i < $count; ++$i) {
            $dtos[] = new InfoDescriptionModelDto(
                id: Uuid::v7()->toRfc4122(),
                libelle: 'Libelle test '.($i + 1),
                description: 'Description test '.($i + 1),
                createdAt: '2000-03-31 12:00:00',
                updatedAt: '2000-03-31 13:00:00'
            );
        }

        return $dtos;
    }
}
