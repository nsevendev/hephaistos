<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Entity\TermsConditionsArticle\Dto;

use DateTimeImmutable;
use Heph\Entity\InfoDescriptionModel\Dto\InfoDescriptionModelCreateDto;
use Heph\Entity\Shared\ValueObject\ArticleValueObject;
use Heph\Entity\Shared\ValueObject\DescriptionValueObject;
use Heph\Entity\Shared\ValueObject\LibelleValueObject;
use Heph\Entity\Shared\ValueObject\TitleValueObject;
use Heph\Entity\TermsConditions\Dto\TermsConditionsCreateDto;
use Heph\Entity\TermsConditionsArticle\Dto\TermsConditionsArticleCreateDto;
use Heph\Tests\Faker\Dto\TermsConditions\TermsConditionsCreateDtoFaker;
use Heph\Tests\Faker\Dto\TermsConditionsArticle\TermsConditionsArticleCreateDtoFaker;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\Uid\Uuid;

#[CoversClass(TermsConditionsArticleCreateDto::class), CoversClass(InfoDescriptionModelCreateDto::class), CoversClass(TitleValueObject::class), CoversClass(ArticleValueObject::class), CoversClass(LibelleValueObject::class), CoversClass(DescriptionValueObject::class), CoversClass(TermsConditionsCreateDto::class)]
class TermsConditionsArticleCreateDtoTest extends HephUnitTestCase
{
    public function testTermsConditionsArticleCreateDto(): void
    {
        $dto = TermsConditionsArticleCreateDtoFaker::new();

        self::assertNotNull($dto);
        self::assertInstanceOf(TermsConditionsArticleCreateDto::class, $dto);

        self::assertNotNull($dto->id());
        self::assertInstanceOf(Uuid::class, $dto->id());
        self::assertInstanceOf(TitleValueObject::class, $dto->title());
        self::assertInstanceOf(ArticleValueObject::class, $dto->article());
        self::assertSame('titre test', $dto->title()->value());
        self::assertSame('article test', $dto->article()->value());
        self::assertInstanceOf(DateTimeImmutable::class, $dto->createdAt());
        self::assertInstanceOf(DateTimeImmutable::class, $dto->updatedAt());

        $termsConditionsDto = $dto->termsConditions();
        self::assertNotNull($termsConditionsDto);
        self::assertInstanceOf(TermsConditionsCreateDto::class, $termsConditionsDto);

        self::assertNotNull($termsConditionsDto->id());
    }

    public function testTermsConditionsArticleCreateDtoWithFunctionNew(): void
    {
        $termsConditions = TermsConditionsCreateDtoFaker::new();
        $dto = TermsConditionsArticleCreateDto::new(
            termsConditions: $termsConditions,
            title: 'Titre manuel',
            article: 'Article manuel',
        );

        self::assertNotNull($dto);
        self::assertInstanceOf(TermsConditionsArticleCreateDto::class, $dto);

        self::assertNotNull($dto->id());
        self::assertInstanceOf(Uuid::class, $dto->id());
        self::assertInstanceOf(DateTimeImmutable::class, $dto->createdAt());
        self::assertInstanceOf(DateTimeImmutable::class, $dto->updatedAt());

        self::assertInstanceOf(TitleValueObject::class, $dto->title());
        self::assertInstanceOf(ArticleValueObject::class, $dto->article());

        self::assertSame('Titre manuel', $dto->title()->value());
        self::assertSame('Article manuel', $dto->article()->value());

        self::assertSame('Titre manuel', (string) $dto->title());
        self::assertSame('Article manuel', (string) $dto->article());

        $termsConditionsDto = $dto->termsConditions();
        self::assertNotNull($termsConditionsDto);
        self::assertInstanceOf(TermsConditionsCreateDto::class, $termsConditionsDto);
        self::assertInstanceOf(Uuid::class, $termsConditionsDto->id());
    }
}
