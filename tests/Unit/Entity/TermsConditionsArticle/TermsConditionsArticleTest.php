<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Entity\TermsConditionsArticle;

use DateTimeImmutable;
use Heph\Entity\InfoDescriptionModel\InfoDescriptionModel;
use Heph\Entity\Shared\ValueObject\DescriptionValueObject;
use Heph\Entity\Shared\ValueObject\LibelleValueObject;
use Heph\Entity\TermsConditions\TermsConditions;
use Heph\Entity\TermsConditionsArticle\TermsConditionsArticle;
use Heph\Entity\TermsConditionsArticle\ValueObject\TermsConditionsArticleArticle;
use Heph\Entity\TermsConditionsArticle\ValueObject\TermsConditionsArticleTitle;
use Heph\Tests\Faker\Entity\TermsConditions\TermsConditionsFaker;
use Heph\Tests\Faker\Entity\TermsConditionsArticle\TermsConditionsArticleFaker;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[
    CoversClass(TermsConditionsArticle::class),
    CoversClass(TermsConditions::class),
    CoversClass(InfoDescriptionModel::class),
    CoversClass(LibelleValueObject::class),
    CoversClass(DescriptionValueObject::class),
    CoversClass(TermsConditionsArticleArticle::class),
    CoversClass(TermsConditionsArticleTitle::class),
]
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
        self::assertSame('titre test', $termsConditionsArticle->title()->value());
        self::assertSame('article test', $termsConditionsArticle->article()->value());
    }

    public function testEntitySetters(): void
    {
        $termsConditionsArticle = TermsConditionsArticleFaker::new();

        $newTermsConditionsUpdated = TermsConditionsFaker::new();
        $termsConditionsArticle->setTermsConditions($newTermsConditionsUpdated);

        self::assertSame($newTermsConditionsUpdated, $termsConditionsArticle->termsConditions());

        $newTitle = 'new title';
        $termsConditionsArticle->setTitle(new TermsConditionsArticleTitle($newTitle));

        self::assertSame($newTitle, $termsConditionsArticle->title()->value());

        $newArticle = 'new article';
        $termsConditionsArticle->setArticle(new TermsConditionsArticleArticle($newArticle));

        self::assertSame($newArticle, $termsConditionsArticle->article()->value());

        $newDateUpdated = new DateTimeImmutable();
        $termsConditionsArticle->setUpdatedAt($newDateUpdated);

        self::assertSame($newDateUpdated, $termsConditionsArticle->updatedAt());
    }
}
