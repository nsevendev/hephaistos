<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Entity\EngineRemap\Dto;

use DateTimeImmutable;
use Heph\Entity\EngineRemap\Dto\EngineRemapCreateDto;
use Heph\Entity\InfoDescriptionModel\InfoDescriptionModel;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\Uid\Uuid;

#[CoversClass(EngineRemapCreateDto::class)]
class EngineRemapCreateDtoTest extends HephUnitTestCase
{
    public function testEngineRemapCreateDto(): void
    {
        $infoDescriptionModel = $this->createMock(InfoDescriptionModel::class);
        $engineRemapDto = new EngineRemapCreateDto(
            id: Uuid::v7(),
            infoDescriptionModel: $infoDescriptionModel,
            createdAt: new DateTimeImmutable(),
            updatedAt: new DateTimeImmutable()
        );

        self::assertNotNull($engineRemapDto);
        self::assertInstanceOf(EngineRemapCreateDto::class, $engineRemapDto);
        self::assertInstanceOf(Uuid::class, $engineRemapDto->id());
        self::assertInstanceOf(InfoDescriptionModel::class, $engineRemapDto->infoDescriptionModel());
        self::assertInstanceOf(DateTimeImmutable::class, $engineRemapDto->createdAt());
        self::assertInstanceOf(DateTimeImmutable::class, $engineRemapDto->updatedAt());
    }

    public function testEngineRemapCreateDtoWithFunctionNew(): void
    {
        $infoDescriptionModel = $this->createMock(InfoDescriptionModel::class);
        $engineRemapDto = EngineRemapCreateDto::new($infoDescriptionModel);

        self::assertNotNull($engineRemapDto);
        self::assertInstanceOf(EngineRemapCreateDto::class, $engineRemapDto);
        self::assertInstanceOf(Uuid::class, $engineRemapDto->id());
        self::assertInstanceOf(InfoDescriptionModel::class, $engineRemapDto->infoDescriptionModel());
        self::assertInstanceOf(DateTimeImmutable::class, $engineRemapDto->createdAt());
        self::assertInstanceOf(DateTimeImmutable::class, $engineRemapDto->updatedAt());
    }
}
