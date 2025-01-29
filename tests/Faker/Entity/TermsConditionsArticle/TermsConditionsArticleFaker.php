<?php

declare(strict_types=1);

namespace Heph\Tests\Faker\Entity\TermsConditionsArticle;

use Heph\Entity\TermsConditionsArticle\TermsConditionsArticle;
use Heph\Tests\Faker\Entity\TermsConditions\TermsConditionsFaker;

final class TermsConditionsArticleFaker
{
    public static function new(): TermsConditionsArticle
    {
        $termsConditions = TermsConditionsFaker::new();

        return new TermsConditionsArticle(
            termsConditions: $termsConditions,
            title: 'titre test',
            article: 'article test'
        );
    }
}
