<?php

declare(strict_types=1);

namespace Heph\Tests\Faker\Dto\TermsConditions;

use Heph\Entity\TermsConditions\Dto\TermsConditionsCreateDto;
use Heph\Tests\Faker\Dto\InfoDescriptionModel\InfoDescriptionModelCreateDtoFaker;

class TermsConditionsCreateDtoFaker
{
    public static function new(): TermsConditionsCreateDto
    {
        return new TermsConditionsCreateDto(
            infoDescriptionModel: InfoDescriptionModelCreateDtoFaker::new()
        );
    }
}
