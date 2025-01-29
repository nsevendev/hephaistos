<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Entity\TermsConditionsArticle\Dto;

use Heph\Entity\TermsConditionsArticle\Dto\TermsConditionsArticleCreateDto;
use Heph\Tests\Faker\Dto\TermsConditionsArticle\TermsConditionsArticleCreateDtoFaker;
use Heph\Tests\Faker\Dto\TermsConditions\TermsConditionsCreateDtoFaker;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(TermsConditionsArticleCreateDto::class)]
class TermsConditionsArticleCreateDtoTest extends HephUnitTestCase
{
    public function testTermsConditionsArticleCreateDto(): void
    {
        $dto = TermsConditionsArticleCreateDtoFaker::new();

        self::assertNotNull($dto);
        self::assertInstanceOf(TermsConditionsArticleCreateDto::class, $dto);

        self::assertSame('1234', $dto->id());
        self::assertSame('titre test', $dto->title());
        self::assertSame('article test', $dto->article());
        self::assertInstanceOf(\DateTimeImmutable::class, $dto->createdAt());
        self::assertInstanceOf(\DateTimeImmutable::class, $dto->updatedAt());

        self::assertSame('2000-03-31 10:00:00', $dto->createdAt()->format('Y-m-d H:i:s'));
        self::assertSame('2000-03-31 12:00:00', $dto->updatedAt()->format('Y-m-d H:i:s'));

        $termsConditionsDto = $dto->termsConditions();
        self::assertNotNull($termsConditionsDto);
        self::assertInstanceOf(\Heph\Entity\TermsConditions\Dto\TermsConditionsCreateDto::class, $termsConditionsDto);

        self::assertSame('1234', $termsConditionsDto->id());
    }

    public function testTermsConditionsArticleCreateDtoWithFunctionNew(): void
    {
        $termsConditions = TermsConditionsCreateDtoFaker::new();
        $dto = TermsConditionsArticleCreateDto::new(
            id: '5678',
            termsConditions: $termsConditions,
            title: 'Titre manuel',
            article: 'Article manuel',
            createdAt: new \DateTimeImmutable('2023-01-01 10:00:00'),
            updatedAt: new \DateTimeImmutable('2023-01-01 12:00:00')
        );

        self::assertNotNull($dto);
        self::assertInstanceOf(TermsConditionsArticleCreateDto::class, $dto);

        self::assertSame('5678', $dto->id());
        self::assertSame('Titre manuel', $dto->title());
        self::assertSame('Article manuel', $dto->article());
        self::assertInstanceOf(\DateTimeImmutable::class, $dto->createdAt());
        self::assertInstanceOf(\DateTimeImmutable::class, $dto->updatedAt());

        self::assertSame('2023-01-01 10:00:00', $dto->createdAt()->format('Y-m-d H:i:s'));
        self::assertSame('2023-01-01 12:00:00', $dto->updatedAt()->format('Y-m-d H:i:s'));

        $termsConditionsDto = $dto->termsConditions();
        self::assertNotNull($termsConditionsDto);
        self::assertInstanceOf(\Heph\Entity\TermsConditions\Dto\TermsConditionsCreateDto::class, $termsConditionsDto);

        self::assertSame('1234', $termsConditionsDto->id());
    }
}