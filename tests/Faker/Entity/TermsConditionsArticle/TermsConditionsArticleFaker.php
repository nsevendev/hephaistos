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

    /**
     * @throws TermsConditionsArticleInvalidArgumentException
     */
    public static function withTitleMoreLonger(): TermsConditionsArticle
    {
        $termsConditions = TermsConditionsFaker::new();

        return new TermsConditionsArticle(
            termsConditions: $termsConditions,
            title: TermsConditionsArticleTitle::fromValue('titre testtitre testtitre testtitre testtitre testtitre testtitre testtitre testtitre testtitre testtitre testtitre testtitre testtitre testtitre testtitre testtitre testtitre testtitre testtitre testtitre testtitre testtitre testtitre testtitre testtitre testtitre testtitre testtitre testtitre testtitre testtitre testtitre testtitre testtitre testtitre testtitre testtitre testtitre testtitre testtitre testtitre testtitre testtitre testtitre testtitre testtitre testtitre testtitre testtitre testtitre testtitre testtitre testtitre testtitre testtitre testtitre testtitre test'),
            article: TermsConditionsArticleArticle::fromValue('article test')
        );
    }

    /**
     * @throws TermsConditionsArticleInvalidArgumentException
     */
    public static function withTitleEmpty(): TermsConditionsArticle
    {
        $termsConditions = TermsConditionsFaker::new();

        return new TermsConditionsArticle(
            termsConditions: $termsConditions,
            title: TermsConditionsArticleTitle::fromValue(''),
            article: TermsConditionsArticleArticle::fromValue('article test')
        );
    }

    /**
     * @throws TermsConditionsArticleInvalidArgumentException
     */
    public static function withArticleMoreLonger(): TermsConditionsArticle
    {
        $termsConditions = TermsConditionsFaker::new();

        return new TermsConditionsArticle(
            termsConditions: $termsConditions,
            title: TermsConditionsArticleTitle::fromValue('titre test'),
            article: TermsConditionsArticleArticle::fromValue('article testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle testarticle test')
        );
    }

    /**
     * @throws TermsConditionsArticleInvalidArgumentException
     */
    public static function withArticleEmpty(): TermsConditionsArticle
    {
        $termsConditions = TermsConditionsFaker::new();

        return new TermsConditionsArticle(
            termsConditions: $termsConditions,
            title: TermsConditionsArticleTitle::fromValue('titre test'),
            article: TermsConditionsArticleArticle::fromValue('')
        );
    }
}
