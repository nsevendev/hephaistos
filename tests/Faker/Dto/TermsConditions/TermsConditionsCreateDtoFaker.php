<?php

declare(strict_types=1);

namespace Heph\Tests\Faker\Dto\TermsConditions;

use Heph\Entity\InfoDescriptionModel\Dto\InfoDescriptionModelCreateDto;
use Heph\Entity\TermsConditions\Dto\TermsConditionsCreateDto;

class TermsConditionsCreateDtoFaker
{
    public static function new(): TermsConditionsCreateDto
    {
        return new TermsConditionsCreateDto(
            infoDescriptionModel: InfoDescriptionModelCreateDto::new('libelle test', 'description test'),
        );
    }
}
