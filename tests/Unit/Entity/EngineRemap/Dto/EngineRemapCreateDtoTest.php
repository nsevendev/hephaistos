<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Entity\EngineRemap\Dto;

use Heph\Entity\EngineRemap\Dto\EngineRemapCreateDto;
use Heph\Entity\InfoDescriptionModel\Dto\InfoDescriptionModelCreateDto;
use Heph\Entity\Shared\ValueObject\DescriptionValueObject;
use Heph\Entity\Shared\ValueObject\LibelleValueObject;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(EngineRemapCreateDto::class), CoversClass(InfoDescriptionModelCreateDto::class), CoversClass(LibelleValueObject::class), CoversClass(DescriptionValueObject::class),]
class EngineRemapCreateDtoTest extends HephUnitTestCase
{
    public function testEngineRemapCreateDto(): void
    {
        $infoDescriptionModel = InfoDescriptionModelCreateDto::new('libelle test', 'description test');
        $engineRemapDto = new EngineRemapCreateDto($infoDescriptionModel);

        self::assertNotNull($engineRemapDto);
        self::assertInstanceOf(EngineRemapCreateDto::class, $engineRemapDto);
        self::assertInstanceOf(InfoDescriptionModelCreateDto::class, $engineRemapDto->infoDescriptionModel);
    }

    public function testEngineRemapCreateDtoWithFunctionNew(): void
    {
        $engineRemapDto = EngineRemapCreateDto::new('libelle test', 'description test');

        self::assertNotNull($engineRemapDto);
        self::assertInstanceOf(EngineRemapCreateDto::class, $engineRemapDto);
        self::assertInstanceOf(InfoDescriptionModelCreateDto::class, $engineRemapDto->infoDescriptionModel);
    }
}
