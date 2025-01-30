<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Entity\InfoDescriptionModel\Dto;

use Heph\Entity\InfoDescriptionModel\Dto\InfoDescriptionModelDto;
use Heph\Tests\Faker\Dto\InfoDescriptionModel\InfoDescriptionModelDtoFaker;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(InfoDescriptionModelDto::class)]
class InfoDescriptionModelDtoTest extends HephUnitTestCase
{
    public function testInfoDescriptionModelDto(): void
    {
        $infoDescriptionModelDto = InfoDescriptionModelDtoFaker::new();

        self::assertNotNull($infoDescriptionModelDto);

        self::assertInstanceOf(InfoDescriptionModelDto::class, $infoDescriptionModelDto);

        self::assertSame('1234', $infoDescriptionModelDto->id);
        self::assertSame('Libelle test', $infoDescriptionModelDto->libelle);
        self::assertSame('Description test', $infoDescriptionModelDto->description);
        self::assertSame('2000-03-31 12:00:00', $infoDescriptionModelDto->createdAt);
        self::assertSame('2000-03-31 13:00:00', $infoDescriptionModelDto->updatedAt);
    }

    public function testInfoDescriptionModelDtoCollection(): void
    {
        $dtos = InfoDescriptionModelDtoFaker::collection(3);

        self::assertNotEmpty($dtos);
        self::assertCount(3, $dtos);

        foreach ($dtos as $index => $dto) {
            self::assertInstanceOf(InfoDescriptionModelDto::class, $dto);

            self::assertSame((string) ($index + 1), $dto->id);
            self::assertSame('Libelle test '.($index + 1), $dto->libelle);
            self::assertSame('Description test '.($index + 1), $dto->description);
            self::assertSame('2000-03-31 12:00:00', $dto->createdAt);
            self::assertSame('2000-03-31 13:00:00', $dto->updatedAt);
        }
    }

    public function testInfoDescriptionModelDtoManualConstruction(): void
    {
        $dto = new InfoDescriptionModelDto(
            id: '5678',
            libelle: 'Un autre libelle test',
            description: 'Une autre description test',
            createdAt: '2023-10-01 10:00:00',
            updatedAt: '2023-10-01 11:00:00'
        );

        self::assertNotNull($dto);

        self::assertInstanceOf(InfoDescriptionModelDto::class, $dto);

        self::assertSame('5678', $dto->id);
        self::assertSame('Un autre libelle test', $dto->libelle);
        self::assertSame('Une autre description test', $dto->description);
        self::assertSame('2023-10-01 10:00:00', $dto->createdAt);
        self::assertSame('2023-10-01 11:00:00', $dto->updatedAt);
    }
}
