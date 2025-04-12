<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Entity\LmQuatre\Dto;

use DateTimeImmutable;
use Heph\Entity\InfoDescriptionModel\Dto\InfoDescriptionModelCreateDto;
use Heph\Entity\LmQuatre\Dto\LmQuatreUpdateDto;
use Heph\Entity\Shared\ValueObject\DescriptionValueObject;
use Heph\Entity\Shared\ValueObject\LibelleValueObject;
use Heph\Tests\Faker\Dto\LmQuatre\LmQuatreUpdateDtoFaker;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(LmQuatreUpdateDto::class), CoversClass(DescriptionValueObject::class), CoversClass(LibelleValueObject::class), CoversClass(InfoDescriptionModelCreateDto::class)]
class LmQuatreUpdateDtoTest extends HephUnitTestCase
{
    public function testLmQuatreUpdateDto(): void
    {
        $infoDescriptionModel = InfoDescriptionModelCreateDto::new('libelle test', 'description test');
        $updateLmQuatreDto = new LmQuatreUpdateDto($infoDescriptionModel, 'Math', 'adresse faker', 'test@test.com', '123456789', new DateTimeImmutable('2000-03-31'));

        self::assertNotNull($updateLmQuatreDto);

        self::assertInstanceOf(LmQuatreUpdateDto::class, $updateLmQuatreDto);

        self::assertSame('Math', $updateLmQuatreDto->owner);
        self::assertSame('adresse faker', $updateLmQuatreDto->adresse);
        self::assertSame('test@test.com', $updateLmQuatreDto->email);
        self::assertSame('123456789', $updateLmQuatreDto->phoneNumber);

        self::assertSame('Math', (string) $updateLmQuatreDto->owner);
        self::assertSame('adresse faker', (string) $updateLmQuatreDto->adresse);
        self::assertSame('test@test.com', (string) $updateLmQuatreDto->email);
        self::assertSame('123456789', (string) $updateLmQuatreDto->phoneNumber);
    }

    public function testLmQuatreUpdateDtoWithFaker(): void
    {
        $updateLmQuatreDto = LmQuatreUpdateDtoFaker::new();

        self::assertNotNull($updateLmQuatreDto);
        self::assertInstanceOf(LmQuatreUpdateDto::class, $updateLmQuatreDto);

        self::assertSame('owner updated', $updateLmQuatreDto->owner);
        self::assertSame('adresse faker', $updateLmQuatreDto->adresse);
        self::assertSame('test@test.com', $updateLmQuatreDto->email);
        self::assertSame('123456789', $updateLmQuatreDto->phoneNumber);

        self::assertSame('owner updated', (string) $updateLmQuatreDto->owner);
        self::assertSame('adresse faker', (string) $updateLmQuatreDto->adresse);
        self::assertSame('test@test.com', (string) $updateLmQuatreDto->email);
        self::assertSame('123456789', (string) $updateLmQuatreDto->phoneNumber);
    }

    public function testLmQuatreUpdateDtoWithFonctionNew(): void
    {
        $updateLmQuatreDto = LmQuatreUpdateDto::new('libelle test', 'description test', 'owner updated', 'adresse faker', 'test@test.com', '123456789', new DateTimeImmutable('2000-03-31'));

        self::assertNotNull($updateLmQuatreDto);
        self::assertInstanceOf(LmQuatreUpdateDto::class, $updateLmQuatreDto);

        self::assertSame('owner updated', $updateLmQuatreDto->owner);
        self::assertSame('adresse faker', $updateLmQuatreDto->adresse);
        self::assertSame('test@test.com', $updateLmQuatreDto->email);
        self::assertSame('123456789', $updateLmQuatreDto->phoneNumber);

        self::assertSame('owner updated', (string) $updateLmQuatreDto->owner);
        self::assertSame('adresse faker', (string) $updateLmQuatreDto->adresse);
        self::assertSame('test@test.com', (string) $updateLmQuatreDto->email);
        self::assertSame('123456789', (string) $updateLmQuatreDto->phoneNumber);
    }
}
