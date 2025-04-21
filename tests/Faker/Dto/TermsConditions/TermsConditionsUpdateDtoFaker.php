<?php

declare(strict_types=1);

namespace Heph\Tests\Faker\Dto\TermsConditions;

use Heph\Entity\TermsConditions\Dto\TermsConditionsUpdateDto;

class TermsConditionsUpdateDtoFaker
{
    public static function new(): TermsConditionsUpdateDto
    {
        return TermsConditionsUpdateDto::new('libelle update', 'description update');
    }
}
