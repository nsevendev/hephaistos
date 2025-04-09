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
use Heph\Infrastructure\ApiResponse\Exception\Custom\AbstractApiResponseException;
use Heph\Infrastructure\ApiResponse\Exception\Custom\TermsConditionsArticle\TermsConditionsArticleInvalidArgumentException;
use Heph\Infrastructure\ApiResponse\Exception\Error\Error;
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
    CoversClass(TermsConditionsArticleInvalidArgumentException::class),
    CoversClass(Error::class),
    CoversClass(AbstractApiResponseException::class),
]
class TermsConditionsArticleTest extends HephUnitTestCase
{
    /**
     * @throws TermsConditionsArticleInvalidArgumentException
     */
    public function testEntityInitialization(): void
    {
        $termsConditionsArticle = TermsConditionsArticleFaker::new();
        $title = 'titre test';
        $article = 'article test';

        self::assertInstanceOf(TermsConditionsArticle::class, $termsConditionsArticle);
        self::assertNotNull($termsConditionsArticle->id());
        self::assertInstanceOf(DateTimeImmutable::class, $termsConditionsArticle->createdAt());
        self::assertInstanceOf(DateTimeImmutable::class, $termsConditionsArticle->updatedAt());
        self::assertNotNull($termsConditionsArticle->termsConditions());
        self::assertSame($title, $termsConditionsArticle->title()->value());
        self::assertSame($article, $termsConditionsArticle->article()->value());
        self::assertSame($title, $termsConditionsArticle->title()->jsonSerialize());
        self::assertSame($article, $termsConditionsArticle->article()->jsonSerialize());
        self::assertSame((string) $title, (string) $termsConditionsArticle->title());
        self::assertSame($article, (string) $termsConditionsArticle->article());
    }

    /**
     * @throws TermsConditionsArticleInvalidArgumentException
     */
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

    public function testEntityWithMTitleMoreLonger(): void
    {
        $this->expectException(TermsConditionsArticleInvalidArgumentException::class);

        $ping = TermsConditionsArticleFaker::withTitleMoreLonger();
    }

    public function testEntityWithTitleEmpty(): void
    {
        $this->expectException(TermsConditionsArticleInvalidArgumentException::class);

        $ping = TermsConditionsArticleFaker::withTitleEmpty();
    }

    public function testEntityWithArticleMoreLonger(): void
    {
        $this->expectException(TermsConditionsArticleInvalidArgumentException::class);

        $ping = TermsConditionsArticleFaker::withArticleMoreLonger();
    }

    public function testEntityWithArticleEmpty(): void
    {
        $this->expectException(TermsConditionsArticleInvalidArgumentException::class);

        $ping = TermsConditionsArticleFaker::withArticleEmpty();
    }
}
