<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Entity\TermsConditions\Dto;

use DateTimeImmutable;
use Heph\Entity\InfoDescriptionModel\Dto\InfoDescriptionModelDto;
use Heph\Entity\InfoDescriptionModel\InfoDescriptionModel;
use Heph\Entity\TermsConditions\Dto\TermsConditionsDto;
use Heph\Entity\TermsConditions\TermsConditions;
use Heph\Tests\Faker\Dto\TermsConditions\TermsConditionsDtoFaker;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\Uid\Uuid;

#[CoversClass(TermsConditionsDto::class), CoversClass(InfoDescriptionModelDto::class)]
class TermsConditionsDtoTest extends HephUnitTestCase
{
    public function testTermsConditionsDto(): void
    {
        $termsConditionsDto = TermsConditionsDtoFaker::new();

        self::assertNotNull($termsConditionsDto);
        self::assertInstanceOf(TermsConditionsDto::class, $termsConditionsDto);
        self::assertNotNull($termsConditionsDto->id);
        self::assertInstanceOf(Uuid::class, $termsConditionsDto->id);
        self::assertSame('2000-03-31 10:00:00', $termsConditionsDto->createdAt);
        self::assertSame('2000-03-31 12:00:00', $termsConditionsDto->updatedAt);

        self::assertNotNull($termsConditionsDto->infoDescriptionModel);
        self::assertSame('Libelle test', $termsConditionsDto->infoDescriptionModel->libelle);
        self::assertSame('Description test', $termsConditionsDto->infoDescriptionModel->description);
    }

    public function testFromEntity(): void
    {
        $infoDescriptionModelMock = $this->createMock(InfoDescriptionModel::class);
        $infoDescriptionModelMock->method('libelle')->willReturn('Libelle test');
        $infoDescriptionModelMock->method('description')->willReturn('Description test');
        $infoDescriptionModelMock->method('createdAt')->willReturn(new DateTimeImmutable('2000-03-31 10:00:00'));
        $infoDescriptionModelMock->method('updatedAt')->willReturn(new DateTimeImmutable('2000-03-31 12:00:00'));

        $termsConditionsMock = $this->createMock(TermsConditions::class);
        $termsConditionsMock->method('infoDescriptionModel')->willReturn($infoDescriptionModelMock);
        $termsConditionsMock->method('createdAt')->willReturn(new DateTimeImmutable('2000-03-31 10:00:00'));
        $termsConditionsMock->method('updatedAt')->willReturn(new DateTimeImmutable('2000-03-31 12:00:00'));

        $dto = TermsConditionsDto::fromEntity($termsConditionsMock);

        self::assertInstanceOf(TermsConditionsDto::class, $dto);
        self::assertNotNull($dto->id);
        self::assertInstanceOf(Uuid::class, $dto->id);
        self::assertSame('2000-03-31 10:00:00', $dto->createdAt);
        self::assertSame('2000-03-31 12:00:00', $dto->updatedAt);

        self::assertInstanceOf(InfoDescriptionModelDto::class, $dto->infoDescriptionModel);
        self::assertSame('Libelle test', $dto->infoDescriptionModel->libelle);
        self::assertSame('Description test', $dto->infoDescriptionModel->description);
    }

    public function testTermsConditionsDtoCollection(): void
    {
        $dtos = TermsConditionsDtoFaker::collection(3);

        self::assertNotEmpty($dtos);
        self::assertCount(3, $dtos);

        foreach ($dtos as $index => $dto) {
            self::assertInstanceOf(TermsConditionsDto::class, $dto);
            self::assertInstanceOf(Uuid::class, $dto->id);
            self::assertSame('2000-03-31 10:00:00', $dto->createdAt);
            self::assertSame('2000-03-31 12:00:00', $dto->updatedAt);

            self::assertNotNull($dto->infoDescriptionModel);
            self::assertSame('Libelle test', $dto->infoDescriptionModel->libelle);
            self::assertSame('Description test', $dto->infoDescriptionModel->description);
        }
    }

    public function testToListTermsConditions(): void
    {
        $termsConditionsMock1 = $this->createMock(TermsConditions::class);
        $termsConditionsMock1->method('createdAt')->willReturn(new DateTimeImmutable('2000-03-31 10:00:00'));
        $termsConditionsMock1->method('updatedAt')->willReturn(new DateTimeImmutable('2000-03-31 12:00:00'));

        $termsConditionsMock2 = $this->createMock(TermsConditions::class);
        $termsConditionsMock2->method('createdAt')->willReturn(new DateTimeImmutable('2001-03-31 10:00:00'));
        $termsConditionsMock2->method('updatedAt')->willReturn(new DateTimeImmutable('2001-03-31 12:00:00'));

        $entities = [$termsConditionsMock1, $termsConditionsMock2];

        $dtos = TermsConditionsDto::toListTermsConditions($entities);

        self::assertCount(2, $dtos);

        self::assertSame('2000-03-31 10:00:00', $dtos[0]->createdAt);
        self::assertSame('2000-03-31 12:00:00', $dtos[0]->updatedAt);

        self::assertSame('2001-03-31 10:00:00', $dtos[1]->createdAt);
        self::assertSame('2001-03-31 12:00:00', $dtos[1]->updatedAt);
    }
}
