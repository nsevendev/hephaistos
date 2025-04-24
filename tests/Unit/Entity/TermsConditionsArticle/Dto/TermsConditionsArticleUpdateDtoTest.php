<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Entity\TermsConditionsArticle\Dto;

use Heph\Entity\InfoDescriptionModel\Dto\InfoDescriptionModelCreateDto;
use Heph\Entity\Shared\ValueObject\DescriptionValueObject;
use Heph\Entity\Shared\ValueObject\LibelleValueObject;
use Heph\Entity\TermsConditionsArticle\Dto\TermsConditionsArticleUpdateDto;
use Heph\Tests\Faker\Dto\TermsConditionsArticle\TermsConditionsArticleUpdateDtoFaker;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(TermsConditionsArticleUpdateDto::class), CoversClass(DescriptionValueObject::class), CoversClass(LibelleValueObject::class), CoversClass(InfoDescriptionModelCreateDto::class)]
class TermsConditionsArticleUpdateDtoTest extends HephUnitTestCase
{
    public function testTermsConditionsArticleUpdateDto(): void
    {
        $updateTermsConditionsArticleDto = new TermsConditionsArticleUpdateDto('title update', 'article update');

        self::assertNotNull($updateTermsConditionsArticleDto);

        self::assertInstanceOf(TermsConditionsArticleUpdateDto::class, $updateTermsConditionsArticleDto);
    }

    public function testTermsConditionsArticleUpdateDtoWithFaker(): void
    {
        $updateTermsConditionsArticleDto = TermsConditionsArticleUpdateDtoFaker::new();

        self::assertNotNull($updateTermsConditionsArticleDto);
        self::assertInstanceOf(TermsConditionsArticleUpdateDto::class, $updateTermsConditionsArticleDto);
    }

    public function testTermsConditionsArticleUpdateDtoWithFonctionNew(): void
    {
        $updateTermsConditionsArticleDto = TermsConditionsArticleUpdateDto::new('title update', 'article update');

        self::assertNotNull($updateTermsConditionsArticleDto);
        self::assertInstanceOf(TermsConditionsArticleUpdateDto::class, $updateTermsConditionsArticleDto);
    }
}
