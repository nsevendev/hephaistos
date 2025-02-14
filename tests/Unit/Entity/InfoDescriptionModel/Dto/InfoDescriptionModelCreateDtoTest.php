<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Entity\InfoDescriptionModel\Dto;

use Heph\Entity\InfoDescriptionModel\Dto\InfoDescriptionModelCreateDto;
use Heph\Entity\Shared\ValueObject\DescriptionValueObject;
use Heph\Entity\Shared\ValueObject\LibelleValueObject;
use Heph\Tests\Faker\Dto\InfoDescriptionModel\InfoDescriptionModelCreateDtoFaker;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(InfoDescriptionModelCreateDto::class), CoversClass(LibelleValueObject::class), CoversClass(DescriptionValueObject::class)]
class InfoDescriptionModelCreateDtoTest extends HephUnitTestCase
{
    public function testInfoDescriptionModelCreateDto(): void
    {
        $infoDescriptionModelCreateDto = InfoDescriptionModelCreateDtoFaker::new();

        self::assertNotNull($infoDescriptionModelCreateDto);

        self::assertInstanceOf(InfoDescriptionModelCreateDto::class, $infoDescriptionModelCreateDto);
        self::assertInstanceOf(LibelleValueObject::class, $infoDescriptionModelCreateDto->libelle());
        self::assertInstanceOf(DescriptionValueObject::class, $infoDescriptionModelCreateDto->description());

        self::assertSame('Libelle test', $infoDescriptionModelCreateDto->libelle()->value());
        self::assertSame('Description test', $infoDescriptionModelCreateDto->description()->value());

        self::assertSame('Libelle test', (string) $infoDescriptionModelCreateDto->libelle());
        self::assertSame('Description test', (string) $infoDescriptionModelCreateDto->description());
    }

    public function testInfoDescriptionModelCreateDtoWithFunctionNew(): void
    {
        $infoDescriptionModelCreateDto = InfoDescriptionModelCreateDto::new(
            libelle: 'Un autre libelle test',
            description: 'Une autre description test',
        );

        self::assertNotNull($infoDescriptionModelCreateDto);

        self::assertInstanceOf(InfoDescriptionModelCreateDto::class, $infoDescriptionModelCreateDto);
        self::assertInstanceOf(LibelleValueObject::class, $infoDescriptionModelCreateDto->libelle());
        self::assertInstanceOf(DescriptionValueObject::class, $infoDescriptionModelCreateDto->description());

        self::assertSame('Un autre libelle test', $infoDescriptionModelCreateDto->libelle()->value());
        self::assertSame('Une autre description test', $infoDescriptionModelCreateDto->description()->value());

        self::assertSame('Un autre libelle test', (string) $infoDescriptionModelCreateDto->libelle());
        self::assertSame('Une autre description test', (string) $infoDescriptionModelCreateDto->description());
    }
}
