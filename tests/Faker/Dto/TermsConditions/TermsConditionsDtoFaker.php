<?php

declare(strict_types=1);

namespace Heph\Tests\Faker\Dto\TermsConditions;

use Heph\Entity\TermsConditions\Dto\TermsConditionsDto;
use Heph\Tests\Faker\Dto\InfoDescriptionModel\InfoDescriptionModelDtoFaker;
use Symfony\Component\Uid\Uuid;

class TermsConditionsDtoFaker
{
    public static function new(): TermsConditionsDto
    {
        return new TermsConditionsDto(
            id: Uuid::v7(),
            infoDescriptionModel: InfoDescriptionModelDtoFaker::new(),
            createdAt: '2000-03-31 10:00:00',
            updatedAt: '2000-03-31 12:00:00'
        );
    }

    /**
     * @return TermsConditionsDto[]
     */
    public static function collection(int $count = 3): array
    {
        $dtos = [];

        for ($i = 0; $i < $count; ++$i) {
            $dtos[] = new TermsConditionsDto(
                id: Uuid::v7(),
                infoDescriptionModel: InfoDescriptionModelDtoFaker::new(),
                createdAt: '2000-03-31 10:00:00',
                updatedAt: '2000-03-31 12:00:00'
            );
        }

        return $dtos;
    }
}
