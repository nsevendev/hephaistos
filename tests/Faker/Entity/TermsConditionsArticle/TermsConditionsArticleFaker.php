<?php

declare(strict_types=1);

namespace Heph\Tests\Faker\Entity\TermsConditionsArticle;

use Heph\Entity\TermsConditionsArticle\TermsConditionsArticle;
use Heph\Entity\TermsConditionsArticle\ValueObject\TermsConditionsArticleArticle;
use Heph\Entity\TermsConditionsArticle\ValueObject\TermsConditionsArticleTitle;
use Heph\Infrastructure\ApiResponse\Exception\Custom\TermsConditionsArticle\TermsConditionsArticleInvalidArgumentException;
use Heph\Tests\Faker\Entity\TermsConditions\TermsConditionsFaker;

final class TermsConditionsArticleFaker
{
    /**
     * @throws TermsConditionsArticleInvalidArgumentException
     */
    public static function new(): TermsConditionsArticle
    {
        $termsConditions = TermsConditionsFaker::new();

        return new TermsConditionsArticle(
            termsConditions: $termsConditions,
            title: TermsConditionsArticleTitle::fromValue('titre test'),
            article: TermsConditionsArticleArticle::fromValue('article test')
        );
    }
}
