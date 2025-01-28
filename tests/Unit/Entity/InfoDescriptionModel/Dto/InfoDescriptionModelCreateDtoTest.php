<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Entity\InfoDescriptionModel\Dto;

use Heph\Entity\InfoDescriptionModel\Dto\InfoDescriptionModelCreateDto;
use Heph\Entity\Shared\ValueObject\LibelleValueObject;
use Heph\Entity\Shared\ValueObject\DescriptionValueObject;
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
        self::assertInstanceOf(\DateTimeImmutable::class, $infoDescriptionModelCreateDto->createdAt());
        self::assertInstanceOf(\DateTimeImmutable::class, $infoDescriptionModelCreateDto->updatedAt());

        self::assertSame('Libelle test', $infoDescriptionModelCreateDto->libelle()->value());
        self::assertSame('Description test', $infoDescriptionModelCreateDto->description()->value());

        self::assertSame('Libelle test', (string) $infoDescriptionModelCreateDto->libelle());
        self::assertSame('Description test', (string) $infoDescriptionModelCreateDto->description());

        self::assertSame('2000-03-31 00:00:00', $infoDescriptionModelCreateDto->createdAt()->format('Y-m-d H:i:s'));
        self::assertSame('2000-03-31 00:00:00', $infoDescriptionModelCreateDto->updatedAt()->format('Y-m-d H:i:s'));
    }

    public function testInfoDescriptionModelCreateDtoWithFunctionNew(): void
    {
        $infoDescriptionModelCreateDto = InfoDescriptionModelCreateDto::new(
            'Ceci est un Libelle test',
            'Ceci est une Description test'
        );

        self::assertNotNull($infoDescriptionModelCreateDto);

        self::assertInstanceOf(InfoDescriptionModelCreateDto::class, $infoDescriptionModelCreateDto);
        self::assertInstanceOf(LibelleValueObject::class, $infoDescriptionModelCreateDto->libelle());
        self::assertInstanceOf(DescriptionValueObject::class, $infoDescriptionModelCreateDto->description());
        self::assertInstanceOf(\DateTimeImmutable::class, $infoDescriptionModelCreateDto->createdAt());
        self::assertInstanceOf(\DateTimeImmutable::class, $infoDescriptionModelCreateDto->updatedAt());

        self::assertSame('Ceci est un Libelle test', $infoDescriptionModelCreateDto->libelle()->value());
        self::assertSame('Ceci est une Description test', $infoDescriptionModelCreateDto->description()->value());

        self::assertSame('Ceci est un Libelle test', (string) $infoDescriptionModelCreateDto->libelle());
        self::assertSame('Ceci est une Description test', (string) $infoDescriptionModelCreateDto->description());

        self::assertNotNull($infoDescriptionModelCreateDto->createdAt());
        self::assertNotNull($infoDescriptionModelCreateDto->updatedAt());
    }
}
