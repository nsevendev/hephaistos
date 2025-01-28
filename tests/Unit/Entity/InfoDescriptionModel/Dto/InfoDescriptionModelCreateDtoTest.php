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

        self::assertSame('1234', $infoDescriptionModelCreateDto->id());
        self::assertSame('Libelle test', $infoDescriptionModelCreateDto->libelle()->value());
        self::assertSame('Description test', $infoDescriptionModelCreateDto->description()->value());
        self::assertSame('2000-03-31 12:00:00', $infoDescriptionModelCreateDto->createdAt()->format('Y-m-d H:i:s'));
        self::assertSame('2000-03-31 13:00:00', $infoDescriptionModelCreateDto->updatedAt()->format('Y-m-d H:i:s'));

        self::assertSame('Libelle test', (string) $infoDescriptionModelCreateDto->libelle());
        self::assertSame('Description test', (string) $infoDescriptionModelCreateDto->description());
    }

    public function testInfoDescriptionModelCreateDtoWithFunctionNew(): void
    {
        $newDate = new \DateTimeImmutable();
    
        $infoDescriptionModelCreateDto = InfoDescriptionModelCreateDto::new(
            id: '5678',
            libelle: 'Un autre libelle test',
            description: 'Une autre description test',
            createdAt: $newDate,
            updatedAt: $newDate
        );
    
        self::assertNotNull($infoDescriptionModelCreateDto);
    
        self::assertInstanceOf(InfoDescriptionModelCreateDto::class, $infoDescriptionModelCreateDto);
        self::assertInstanceOf(LibelleValueObject::class, $infoDescriptionModelCreateDto->libelle());
        self::assertInstanceOf(DescriptionValueObject::class, $infoDescriptionModelCreateDto->description());
        self::assertInstanceOf(\DateTimeImmutable::class, $infoDescriptionModelCreateDto->createdAt());
        self::assertInstanceOf(\DateTimeImmutable::class, $infoDescriptionModelCreateDto->updatedAt());
    
        self::assertSame('5678', $infoDescriptionModelCreateDto->id());
        self::assertSame('Un autre libelle test', $infoDescriptionModelCreateDto->libelle()->value());
        self::assertSame('Une autre description test', $infoDescriptionModelCreateDto->description()->value());
        self::assertSame($newDate->format('Y-m-d H:i:s'), $infoDescriptionModelCreateDto->createdAt()->format('Y-m-d H:i:s'));
        self::assertSame($newDate->format('Y-m-d H:i:s'), $infoDescriptionModelCreateDto->updatedAt()->format('Y-m-d H:i:s'));
    
        self::assertSame('Un autre libelle test', (string) $infoDescriptionModelCreateDto->libelle());
        self::assertSame('Une autre description test', (string) $infoDescriptionModelCreateDto->description());
    }
}