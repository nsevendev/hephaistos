<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Entity\TermsConditions\Dto;

use Heph\Entity\TermsConditions\Dto\TermsConditionsDto;
use Heph\Tests\Faker\Dto\TermsConditions\TermsConditionsDtoFaker;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(TermsConditionsDto::class)]
class TermsConditionsDtoTest extends HephUnitTestCase
{
    public function testTermsConditionsDto(): void
    {
        $termsConditionsDto = TermsConditionsDtoFaker::new();

        self::assertNotNull($termsConditionsDto);

        self::assertInstanceOf(TermsConditionsDto::class, $termsConditionsDto);
        self::assertSame('1234', $termsConditionsDto->id);
        self::assertSame('2000-03-31 10:00:00', $termsConditionsDto->createdAt);
        self::assertSame('2000-03-31 12:00:00', $termsConditionsDto->updatedAt);

        self::assertNotNull($termsConditionsDto->infoDescriptionModel);
        self::assertSame('Libelle test', $termsConditionsDto->infoDescriptionModel->libelle);
        self::assertSame('Description test', $termsConditionsDto->infoDescriptionModel->description);
    }

    public function testTermsConditionsDtoCollection(): void
    {
        $dtos = TermsConditionsDtoFaker::collection(3);

        self::assertNotEmpty($dtos);
        self::assertCount(3, $dtos);

        foreach ($dtos as $index => $dto) {
            self::assertInstanceOf(TermsConditionsDto::class, $dto);
            self::assertSame((string) ($index + 1), $dto->id);
            self::assertSame('2000-03-31 10:00:00', $dto->createdAt);
            self::assertSame('2000-03-31 12:00:00', $dto->updatedAt);

            self::assertNotNull($dto->infoDescriptionModel);
            self::assertSame('Libelle test', $dto->infoDescriptionModel->libelle);
            self::assertSame('Description test', $dto->infoDescriptionModel->description);
        }
    }
}
