<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Entity\TermsConditions\Dto;

use Heph\Entity\TermsConditions\Dto\TermsConditionsCreateDto;
use Heph\Entity\Shared\ValueObject\LibelleValueObject;
use Heph\Entity\Shared\ValueObject\DescriptionValueObject;
use Heph\Tests\Faker\Dto\TermsConditions\TermsConditionsCreateDtoFaker;
use Heph\Entity\InfoDescriptionModel\Dto\InfoDescriptionModelDto;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(TermsConditionsCreateDto::class), CoversClass(LibelleValueObject::class), CoversClass(DescriptionValueObject::class)]
class TermsConditionsCreateDtoTest extends HephUnitTestCase
{
    public function testTermsConditionsCreateDto(): void
    {
        $termsConditionsCreateDto = TermsConditionsCreateDtoFaker::new();

        self::assertNotNull($termsConditionsCreateDto);
        self::assertInstanceOf(TermsConditionsCreateDto::class, $termsConditionsCreateDto);

        $infoDescriptionModel = $termsConditionsCreateDto->infoDescriptionModel();

        self::assertNotNull($infoDescriptionModel->id);
        self::assertSame('Libelle test', $infoDescriptionModel->libelle);
        self::assertSame('Description test', $infoDescriptionModel->description);
        self::assertSame('2000-03-31 00:00:00', $infoDescriptionModel->createdAt);
        self::assertSame('2000-03-31 00:00:00', $infoDescriptionModel->updatedAt);
    }
}
