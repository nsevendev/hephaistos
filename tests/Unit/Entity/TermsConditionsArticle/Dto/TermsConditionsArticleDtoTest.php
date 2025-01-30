<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Entity\TermsConditionsArticle\Dto;

use Heph\Entity\TermsConditionsArticle\Dto\TermsConditionsArticleDto;
use Heph\Tests\Faker\Dto\TermsConditionsArticle\TermsConditionsArticleDtoFaker;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(TermsConditionsArticleDto::class)]
class TermsConditionsArticleDtoTest extends HephUnitTestCase
{
    public function testTermsConditionsArticleDto(): void
    {
        $termsConditionsArticleDto = TermsConditionsArticleDtoFaker::new();

        self::assertNotNull($termsConditionsArticleDto);

        self::assertInstanceOf(TermsConditionsArticleDto::class, $termsConditionsArticleDto);
        self::assertSame('1234', $termsConditionsArticleDto->id);
        self::assertSame('Titre test', $termsConditionsArticleDto->title);
        self::assertSame('Article de test', $termsConditionsArticleDto->article);
        self::assertSame('2000-03-31 10:00:00', $termsConditionsArticleDto->createdAt);
        self::assertSame('2000-03-31 12:00:00', $termsConditionsArticleDto->updatedAt);

        self::assertNotNull($termsConditionsArticleDto->termsConditions);
        self::assertSame('1234', $termsConditionsArticleDto->termsConditions->id);
        self::assertSame('Libelle test', $termsConditionsArticleDto->termsConditions->infoDescriptionModel->libelle);
    }

    public function testTermsConditionsArticleDtoCollection(): void
    {
        $dtos = TermsConditionsArticleDtoFaker::collection(3);

        self::assertNotEmpty($dtos);
        self::assertCount(3, $dtos);

        foreach ($dtos as $index => $dto) {
            self::assertInstanceOf(TermsConditionsArticleDto::class, $dto);
            self::assertSame((string) ($index + 1), $dto->id);
            self::assertSame('Titre test '.($index + 1), $dto->title);
            self::assertSame('Article de test '.($index + 1), $dto->article);
            self::assertSame('2000-03-31 10:00:00', $dto->createdAt);
            self::assertSame('2000-03-31 12:00:00', $dto->updatedAt);

            self::assertNotNull($dto->termsConditions);
            self::assertSame('1234', $dto->termsConditions->id);
        }
    }
}
