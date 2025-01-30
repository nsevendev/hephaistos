<?php

declare(strict_types=1);

namespace Heph\Tests\Faker\Entity\TermsConditions;

use Heph\Entity\TermsConditions\TermsConditions;
use Heph\Tests\Faker\Entity\InfoDescriptionModel\InfoDescriptionModelFaker;

final class TermsConditionsFaker
{
    public static function new(): TermsConditions
    {
        $infoDescriptionModel = InfoDescriptionModelFaker::new();

        return new TermsConditions($infoDescriptionModel);
    }
}
