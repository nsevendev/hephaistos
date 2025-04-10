<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Entity\TermsConditions\Dto;

use DateTimeImmutable;
use Heph\Entity\InfoDescriptionModel\Dto\InfoDescriptionModelCreateDto;
use Heph\Entity\Shared\ValueObject\DescriptionValueObject;
use Heph\Entity\Shared\ValueObject\LibelleValueObject;
use Heph\Entity\TermsConditions\Dto\TermsConditionsCreateDto;
use Heph\Tests\Faker\Dto\InfoDescriptionModel\InfoDescriptionModelCreateDtoFaker;
use Heph\Tests\Faker\Dto\TermsConditions\TermsConditionsCreateDtoFaker;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(TermsConditionsCreateDto::class), CoversClass(InfoDescriptionModelCreateDto::class), CoversClass(LibelleValueObject::class), CoversClass(DescriptionValueObject::class)]
class TermsConditionsCreateDtoTest extends HephUnitTestCase
{
    public function testTermsConditionsCreateDto(): void
    {
        $termsConditionsCreateDto = TermsConditionsCreateDtoFaker::new();

        self::assertNotNull($termsConditionsCreateDto);

        self::assertInstanceOf(TermsConditionsCreateDto::class, $termsConditionsCreateDto);
        self::assertInstanceOf(InfoDescriptionModelCreateDto::class, $termsConditionsCreateDto->infoDescriptionModel());

        self::assertSame('Libelle test', $termsConditionsCreateDto->infoDescriptionModel()->libelle());
        self::assertSame('Description test', $termsConditionsCreateDto->infoDescriptionModel()->description());
    }

    public function testTermsConditionsCreateDtoWithFunctionNew(): void
    {
        $infoDescriptionModelCreateDto = InfoDescriptionModelCreateDtoFaker::new();
        $newDate = new DateTimeImmutable();

        $termsConditionsCreateDto = TermsConditionsCreateDto::new(
            infoDescriptionModel: $infoDescriptionModelCreateDto,
        );

        self::assertNotNull($termsConditionsCreateDto);

        self::assertInstanceOf(TermsConditionsCreateDto::class, $termsConditionsCreateDto);
        self::assertInstanceOf(InfoDescriptionModelCreateDto::class, $termsConditionsCreateDto->infoDescriptionModel());

        self::assertSame('Libelle test', $termsConditionsCreateDto->infoDescriptionModel()->libelle());
        self::assertSame('Description test', $termsConditionsCreateDto->infoDescriptionModel()->description());
    }
}
