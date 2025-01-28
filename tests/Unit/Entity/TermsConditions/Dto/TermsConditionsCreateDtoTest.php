<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Entity\TermsConditions\Dto;

use Heph\Entity\InfoDescriptionModel\Dto\InfoDescriptionModelCreateDto;
use Heph\Entity\TermsConditions\Dto\TermsConditionsCreateDto;
use Heph\Entity\Shared\ValueObject\DescriptionValueObject;
use Heph\Entity\Shared\ValueObject\LibelleValueObject;
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
        self::assertInstanceOf(\DateTimeImmutable::class, $termsConditionsCreateDto->createdAt());
        self::assertInstanceOf(\DateTimeImmutable::class, $termsConditionsCreateDto->updatedAt());

        self::assertSame('1234', $termsConditionsCreateDto->id());
        self::assertSame('1234', $termsConditionsCreateDto->infoDescriptionModel()->id());
        self::assertSame('Libelle test', $termsConditionsCreateDto->infoDescriptionModel()->libelle()->value());
        self::assertSame('Description test', $termsConditionsCreateDto->infoDescriptionModel()->description()->value());
        self::assertSame('2000-03-31 10:00:00', $termsConditionsCreateDto->createdAt()->format('Y-m-d H:i:s'));
        self::assertSame('2000-03-31 12:00:00', $termsConditionsCreateDto->updatedAt()->format('Y-m-d H:i:s'));
    }

    public function testTermsConditionsCreateDtoWithFunctionNew(): void
    {
        $infoDescriptionModelCreateDto = InfoDescriptionModelCreateDtoFaker::new();
        $newDate = new \DateTimeImmutable();

        $termsConditionsCreateDto = TermsConditionsCreateDto::new(
            id: '9876',
            infoDescriptionModel: $infoDescriptionModelCreateDto,
            createdAt: $newDate,
            updatedAt: $newDate
        );

        self::assertNotNull($termsConditionsCreateDto);

        self::assertInstanceOf(TermsConditionsCreateDto::class, $termsConditionsCreateDto);
        self::assertInstanceOf(InfoDescriptionModelCreateDto::class, $termsConditionsCreateDto->infoDescriptionModel());
        self::assertInstanceOf(\DateTimeImmutable::class, $termsConditionsCreateDto->createdAt());
        self::assertInstanceOf(\DateTimeImmutable::class, $termsConditionsCreateDto->updatedAt());

        self::assertSame('9876', $termsConditionsCreateDto->id());
        self::assertSame('1234', $termsConditionsCreateDto->infoDescriptionModel()->id());
        self::assertSame('Libelle test', $termsConditionsCreateDto->infoDescriptionModel()->libelle()->value());
        self::assertSame('Description test', $termsConditionsCreateDto->infoDescriptionModel()->description()->value());
        self::assertSame($newDate->format('Y-m-d H:i:s'), $termsConditionsCreateDto->createdAt()->format('Y-m-d H:i:s'));
        self::assertSame($newDate->format('Y-m-d H:i:s'), $termsConditionsCreateDto->updatedAt()->format('Y-m-d H:i:s'));
    }
}
