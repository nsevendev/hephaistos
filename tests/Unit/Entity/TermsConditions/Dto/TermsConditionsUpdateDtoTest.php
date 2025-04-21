<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Entity\TermsConditions\Dto;

use Heph\Entity\InfoDescriptionModel\Dto\InfoDescriptionModelCreateDto;
use Heph\Entity\Shared\ValueObject\DescriptionValueObject;
use Heph\Entity\Shared\ValueObject\LibelleValueObject;
use Heph\Entity\TermsConditions\Dto\TermsConditionsUpdateDto;
use Heph\Tests\Faker\Dto\TermsConditions\TermsConditionsUpdateDtoFaker;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(TermsConditionsUpdateDto::class), CoversClass(DescriptionValueObject::class), CoversClass(LibelleValueObject::class), CoversClass(InfoDescriptionModelCreateDto::class)]
class TermsConditionsUpdateDtoTest extends HephUnitTestCase
{
    public function testTermsConditionsUpdateDto(): void
    {
        $infoDescriptionModel = InfoDescriptionModelCreateDto::new('libelle test', 'description test');
        $updateTermsConditionsDto = new TermsConditionsUpdateDto($infoDescriptionModel);

        self::assertNotNull($updateTermsConditionsDto);

        self::assertInstanceOf(TermsConditionsUpdateDto::class, $updateTermsConditionsDto);
    }

    public function testTermsConditionsUpdateDtoWithFaker(): void
    {
        $updateTermsConditionsDto = TermsConditionsUpdateDtoFaker::new();

        self::assertNotNull($updateTermsConditionsDto);
        self::assertInstanceOf(TermsConditionsUpdateDto::class, $updateTermsConditionsDto);
    }

    public function testTermsConditionsUpdateDtoWithFonctionNew(): void
    {
        $updateTermsConditionsDto = TermsConditionsUpdateDto::new('libelle test', 'description test');

        self::assertNotNull($updateTermsConditionsDto);
        self::assertInstanceOf(TermsConditionsUpdateDto::class, $updateTermsConditionsDto);
    }
}
