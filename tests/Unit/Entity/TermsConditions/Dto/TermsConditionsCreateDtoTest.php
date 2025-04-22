<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Entity\TermsConditions\Dto;

use Heph\Entity\InfoDescriptionModel\Dto\InfoDescriptionModelCreateDto;
use Heph\Entity\Shared\ValueObject\DescriptionValueObject;
use Heph\Entity\Shared\ValueObject\LibelleValueObject;
use Heph\Entity\TermsConditions\Dto\TermsConditionsCreateDto;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(TermsConditionsCreateDto::class), CoversClass(InfoDescriptionModelCreateDto::class), CoversClass(LibelleValueObject::class), CoversClass(DescriptionValueObject::class),]
class TermsConditionsCreateDtoTest extends HephUnitTestCase
{
    public function testTermsConditionsCreateDto(): void
    {
        $infoDescriptionModel = InfoDescriptionModelCreateDto::new('libelle test', 'description test');
        $termsConditionsDto = new TermsConditionsCreateDto($infoDescriptionModel);

        self::assertNotNull($termsConditionsDto);
        self::assertInstanceOf(TermsConditionsCreateDto::class, $termsConditionsDto);
        self::assertInstanceOf(InfoDescriptionModelCreateDto::class, $termsConditionsDto->infoDescriptionModel);
    }

    public function testTermsConditionsCreateDtoWithFunctionNew(): void
    {
        $termsConditionsDto = TermsConditionsCreateDto::new('libelle test', 'description test');

        self::assertNotNull($termsConditionsDto);
        self::assertInstanceOf(TermsConditionsCreateDto::class, $termsConditionsDto);
        self::assertInstanceOf(InfoDescriptionModelCreateDto::class, $termsConditionsDto->infoDescriptionModel);
    }
}
