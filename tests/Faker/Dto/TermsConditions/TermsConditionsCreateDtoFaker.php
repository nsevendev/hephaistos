<?php

declare(strict_types=1);

namespace Heph\Tests\Faker\Dto\TermsConditions;

use Heph\Entity\TermsConditions\Dto\TermsConditionsCreateDto;
use Heph\Entity\InfoDescriptionModel\Dto\InfoDescriptionModelDto;
use Heph\Tests\Faker\Dto\InfoDescriptionModel\InfoDescriptionModelCreateDtoFaker;

class TermsConditionsCreateDtoFaker
{
    public static function new(?InfoDescriptionModelDto $infoDescriptionModelDto = null): TermsConditionsCreateDto
    {
        if (!$infoDescriptionModelDto) {
            $infoDescriptionModelCreateDto = InfoDescriptionModelCreateDtoFaker::new();
            $infoDescriptionModelDto = new InfoDescriptionModelDto(
                id: uniqid(''),
                libelle: $infoDescriptionModelCreateDto->libelle()->value(),
                description: $infoDescriptionModelCreateDto->description()->value(),
                createdAt: '2000-03-31 00:00:00',
                updatedAt: '2000-03-31 00:00:00'
            );
        }
    
        return new TermsConditionsCreateDto(
            infoDescriptionModel: $infoDescriptionModelDto
        );
    }
}