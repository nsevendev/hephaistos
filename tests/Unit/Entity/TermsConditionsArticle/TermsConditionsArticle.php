<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Entity\TermsConditionsArticle;

use DateTimeImmutable;
use Heph\Entity\InfoDescriptionModel\InfoDescriptionModel;
use Heph\Entity\TermsConditionsArticle\TermsConditionsArticle;
use Heph\Tests\Faker\Entity\InfoDescriptionModel\InfoDescriptionModelFaker;
use Heph\Tests\Faker\Entity\TermsConditionsArticle\TermsConditionsArticleFaker;
use Heph\Tests\Faker\Entity\TermsConditions\TermsConditionsFaker;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(TermsConditionsArticle::class), CoversClass(InfoDescriptionModel::class)]
class TermsConditionsArticleTest extends HephUnitTestCase
{
    public function testEntityInitialization(): void
    {
        $termsConditionsArticle = TermsConditionsArticleFaker::new();

        self::assertInstanceOf(TermsConditionsArticle::class, $termsConditionsArticle);
        self::assertNotNull($termsConditionsArticle->id());
        self::assertInstanceOf(DateTimeImmutable::class, $termsConditionsArticle->createdAt());
        self::assertInstanceOf(DateTimeImmutable::class, $termsConditionsArticle->updatedAt());
        self::assertNotNull($termsConditionsArticle->termsConditions());
        self::assertSame('titre test', $termsConditionsArticle->title());
        self::assertSame('article test', $termsConditionsArticle->article());
    }

    public function testEntitySetters(): void
    {
        $termsConditionsArticle = TermsConditionsArticleFaker::new();

        $newDateUpdated = new DateTimeImmutable();
        $termsConditionsArticle->setUpdatedAt($newDateUpdated);

        self::assertSame($newDateUpdated, $termsConditionsArticle->updatedAt());

        $newTermsConditionsUpdated = TermsConditionsFaker::new();
        $termsConditionsArticle->setTermsConditions($newTermsConditionsUpdated);

        self::assertSame($newTermsConditionsUpdated, $termsConditionsArticle->termsConditions());
    }
}
