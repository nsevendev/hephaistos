<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Entity\EngineRemap\Dto;

use Heph\Entity\EngineRemap\Dto\EngineRemapUpdateDto;
use Heph\Entity\Shared\ValueObject\DescriptionValueObject;
use Heph\Entity\Shared\ValueObject\LibelleValueObject;
use Heph\Tests\Faker\Dto\EngineRemap\EngineRemapUpdateDtoFaker;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(EngineRemapUpdateDto::class), CoversClass(DescriptionValueObject::class), CoversClass(LibelleValueObject::class)]
class EngineRemapUpdateDtoTest extends HephUnitTestCase
{
    public function testEngineRemapUpdateDto(): void
    {
        $updateEngineRemapDto = new EngineRemapUpdateDto(new LibelleValueObject('libelle test'), new DescriptionValueObject('description test'));

        self::assertNotNull($updateEngineRemapDto);

        self::assertInstanceOf(EngineRemapUpdateDto::class, $updateEngineRemapDto);
        self::assertInstanceOf(LibelleValueObject::class, $updateEngineRemapDto->libelle());
        self::assertInstanceOf(DescriptionValueObject::class, $updateEngineRemapDto->description());

        self::assertSame('libelle test', (string) $updateEngineRemapDto->libelle());
        self::assertSame('description test', (string) $updateEngineRemapDto->description());
    }

    public function testEngineRemapUpdateDtoWithFaker(): void
    {
        $updateEngineRemapDto = EngineRemapUpdateDtoFaker::new();

        self::assertNotNull($updateEngineRemapDto);
        self::assertInstanceOf(EngineRemapUpdateDto::class, $updateEngineRemapDto);
        self::assertInstanceOf(LibelleValueObject::class, $updateEngineRemapDto->libelle());
        self::assertInstanceOf(DescriptionValueObject::class, $updateEngineRemapDto->description());

        self::assertSame('libelle update', (string) $updateEngineRemapDto->libelle());
        self::assertSame('description update', (string) $updateEngineRemapDto->description());
    }
}
