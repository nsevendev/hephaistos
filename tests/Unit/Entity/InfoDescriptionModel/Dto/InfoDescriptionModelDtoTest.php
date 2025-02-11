<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Entity\InfoDescriptionModel\Dto;

use Heph\Entity\InfoDescriptionModel\Dto\InfoDescriptionModelDto;
use Heph\Entity\InfoDescriptionModel\InfoDescriptionModel;
use Heph\Tests\Faker\Dto\InfoDescriptionModel\InfoDescriptionModelDtoFaker;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\Uid\Uuid;

#[CoversClass(InfoDescriptionModelDto::class)]
class InfoDescriptionModelDtoTest extends HephUnitTestCase
{
    public function testInfoDescriptionModelDto(): void
    {
        $infoDescriptionModelDto = InfoDescriptionModelDtoFaker::new();

        self::assertNotNull($infoDescriptionModelDto);

        self::assertInstanceOf(InfoDescriptionModelDto::class, $infoDescriptionModelDto);

        self::assertNotNull($infoDescriptionModelDto->id);
        self::assertInstanceOf(Uuid::class, $infoDescriptionModelDto->id);
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

            self::assertSame('Libelle test '.($index + 1), $dto->libelle);
            self::assertSame('Description test '.($index + 1), $dto->description);
            self::assertSame('2000-03-31 12:00:00', $dto->createdAt);
            self::assertSame('2000-03-31 13:00:00', $dto->updatedAt);
        }
    }

    public function testFromArray(): void
    {
        $infoDescriptionModelMock = $this->createMock(InfoDescriptionModel::class);
        $infoDescriptionModelMock->method('libelle')->willReturn('Libelle test');
        $infoDescriptionModelMock->method('description')->willReturn('Description test');
        $infoDescriptionModelMock->method('createdAt')->willReturn(new \DateTimeImmutable('2000-03-31 12:00:00'));
        $infoDescriptionModelMock->method('updatedAt')->willReturn(new \DateTimeImmutable('2000-03-31 13:00:00'));

        $dto = InfoDescriptionModelDto::fromArray($infoDescriptionModelMock);

        self::assertInstanceOf(InfoDescriptionModelDto::class, $dto);
        self::assertNotNull($dto->id);
        self::assertSame('Libelle test', $dto->libelle);
        self::assertSame('Description test', $dto->description);
        self::assertSame('2000-03-31 12:00:00', $dto->createdAt);
        self::assertSame('2000-03-31 13:00:00', $dto->updatedAt);
    }

    public function testToListInfoDescriptionModel(): void
    {
        $infoDescriptionModelMock1 = $this->createMock(InfoDescriptionModel::class);
        $infoDescriptionModelMock1->method('libelle')->willReturn('Libelle test 1');
        $infoDescriptionModelMock1->method('description')->willReturn('Description test 1');
        $infoDescriptionModelMock1->method('createdAt')->willReturn(new \DateTimeImmutable('2000-03-31 12:00:00'));
        $infoDescriptionModelMock1->method('updatedAt')->willReturn(new \DateTimeImmutable('2000-03-31 13:00:00'));

        $infoDescriptionModelMock2 = $this->createMock(InfoDescriptionModel::class);
        $infoDescriptionModelMock2->method('libelle')->willReturn('Libelle test 2');
        $infoDescriptionModelMock2->method('description')->willReturn('Description test 2');
        $infoDescriptionModelMock2->method('createdAt')->willReturn(new \DateTimeImmutable('2001-03-31 12:00:00'));
        $infoDescriptionModelMock2->method('updatedAt')->willReturn(new \DateTimeImmutable('2001-03-31 13:00:00'));

        $entities = [$infoDescriptionModelMock1, $infoDescriptionModelMock2];

        $dtos = InfoDescriptionModelDto::toListInfoDescriptionModel($entities);

        self::assertCount(2, $dtos);

        self::assertNotNull($dtos[0]->id);
        self::assertSame('Libelle test 1', $dtos[0]->libelle);
        self::assertSame('Description test 1', $dtos[0]->description);
        self::assertSame('2000-03-31 12:00:00', $dtos[0]->createdAt);
        self::assertSame('2000-03-31 13:00:00', $dtos[0]->updatedAt);

        self::assertNotNull($dtos[1]->id);
        self::assertSame('Libelle test 2', $dtos[1]->libelle);
        self::assertSame('Description test 2', $dtos[1]->description);
        self::assertSame('2001-03-31 12:00:00', $dtos[1]->createdAt);
        self::assertSame('2001-03-31 13:00:00', $dtos[1]->updatedAt);
    }
}
