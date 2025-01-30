<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Entity\TermsConditions;

use DateTimeImmutable;
use Doctrine\Common\Collections\Collection;
use Heph\Entity\InfoDescriptionModel\InfoDescriptionModel;
use Heph\Entity\TermsConditions\TermsConditions;
use Heph\Entity\TermsConditionsArticle\TermsConditionsArticle;
use Heph\Tests\Faker\Entity\InfoDescriptionModel\InfoDescriptionModelFaker;
use Heph\Tests\Faker\Entity\TermsConditions\TermsConditionsFaker;
use Heph\Tests\Faker\Entity\TermsConditionsArticle\TermsConditionsArticleFaker;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(TermsConditions::class), CoversClass(InfoDescriptionModel::class), CoversClass(TermsConditionsArticle::class)]
class TermsConditionsTest extends HephUnitTestCase
{
    public function testEntityInitialization(): void
    {
        $termsConditions = TermsConditionsFaker::new();

        self::assertInstanceOf(TermsConditions::class, $termsConditions);
        self::assertNotNull($termsConditions->id());
        self::assertInstanceOf(DateTimeImmutable::class, $termsConditions->createdAt());
        self::assertInstanceOf(DateTimeImmutable::class, $termsConditions->updatedAt());
        self::assertNotNull($termsConditions->infoDescriptionModel());
        self::assertInstanceOf(Collection::class, $termsConditions->listTermsConditionsArticle());
        self::assertCount(0, $termsConditions->listTermsConditionsArticle());
    }

    public function testEntitySetters(): void
    {
        $termsConditions = TermsConditionsFaker::new();

        $newDateUpdated = new DateTimeImmutable();
        $termsConditions->setUpdatedAt($newDateUpdated);

        self::assertSame($newDateUpdated, $termsConditions->updatedAt());

        $newInfoDescriptionModelUpdated = InfoDescriptionModelFaker::new();
        $termsConditions->setInfoDescriptionModel($newInfoDescriptionModelUpdated);

        self::assertSame($newInfoDescriptionModelUpdated, $termsConditions->infoDescriptionModel());
    }

    public function testAddTermsConditionsArticle(): void
    {
        $termsConditions = TermsConditionsFaker::new();
        $article = TermsConditionsArticleFaker::new();

        $termsConditions->addTermsConditionsArticle($article);

        self::assertCount(1, $termsConditions->listTermsConditionsArticle());
        self::assertTrue($termsConditions->listTermsConditionsArticle()->contains($article));
        self::assertSame($termsConditions, $article->termsConditions());
    }

    public function testRemoveTermsConditionsArticle(): void
    {
        $termsConditions = TermsConditionsFaker::new();
        $article = TermsConditionsArticleFaker::new();

        $termsConditions->addTermsConditionsArticle($article);
        $termsConditions->removeTermsConditionsArticle($article);

        self::assertCount(0, $termsConditions->listTermsConditionsArticle());
        self::assertFalse($termsConditions->listTermsConditionsArticle()->contains($article));
    }

    public function testAddSameArticleMultipleTimes(): void
    {
        $termsConditions = TermsConditionsFaker::new();
        $article = TermsConditionsArticleFaker::new();

        $termsConditions->addTermsConditionsArticle($article);
        $termsConditions->addTermsConditionsArticle($article);

        self::assertCount(1, $termsConditions->listTermsConditionsArticle());
    }
}
