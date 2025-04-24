<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Entity\TermsConditionsArticle\Dto;

use Heph\Entity\InfoDescriptionModel\Dto\InfoDescriptionModelCreateDto;
use Heph\Entity\Shared\ValueObject\DescriptionValueObject;
use Heph\Entity\Shared\ValueObject\LibelleValueObject;
use Heph\Entity\TermsConditions\Dto\TermsConditionsCreateDto;
use Heph\Entity\TermsConditionsArticle\Dto\TermsConditionsArticleCreateDto;
use Heph\Tests\Faker\Dto\TermsConditionsArticle\TermsConditionsArticleCreateDtoFaker;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(TermsConditionsArticleCreateDto::class), CoversClass(InfoDescriptionModelCreateDto::class), CoversClass(LibelleValueObject::class), CoversClass(DescriptionValueObject::class), CoversClass(TermsConditionsCreateDto::class)]
class TermsConditionsArticleCreateDtoTest extends HephUnitTestCase
{
    public function testTermsConditionsArticleCreateDto(): void
    {
        $dto = TermsConditionsArticleCreateDtoFaker::new('1234');

        self::assertNotNull($dto);
        self::assertInstanceOf(TermsConditionsArticleCreateDto::class, $dto);

        self::assertSame('titre test', $dto->title());
        self::assertSame('article test', $dto->article());
        self::assertSame('1234', $dto->termsConditionsId());
    }

    public function testTermsConditionsArticleCreateDtoWithFunctionNew(): void
    {
        $dto = TermsConditionsArticleCreateDto::new(
            termsConditionsId: '123456789',
            title: 'Titre manuel',
            article: 'Article manuel',
        );

        self::assertNotNull($dto);
        self::assertInstanceOf(TermsConditionsArticleCreateDto::class, $dto);

        self::assertSame('Titre manuel', $dto->title());
        self::assertSame('Article manuel', $dto->article());
        self::assertSame('123456789', $dto->termsConditionsId());
    }
}
