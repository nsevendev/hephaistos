<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Entity\EngineRemap\Dto;

use Heph\Entity\EngineRemap\Dto\EngineRemapUpdateDto;
use Heph\Tests\Faker\Dto\EngineRemap\EngineRemapUpdateDtoFaker;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(EngineRemapUpdateDto::class)]
class EngineRemapUpdateDtoTest extends HephUnitTestCase
{
    public function testEngineRemapUpdateDto(): void
    {
        $engineRemapDto = new EngineRemapUpdateDto('libelle test', 'description test');

        self::assertNotNull($engineRemapDto);
        self::assertInstanceOf(EngineRemapUpdateDto::class, $engineRemapDto);
        self::assertSame('libelle test', $engineRemapDto->libelle());
        self::assertSame('description test', $engineRemapDto->description());
    }

    public function testEngineRemapUpdateDtoWithFaker(): void
    {
        $engineRemapDto = EngineRemapUpdateDtoFaker::new();

        self::assertNotNull($engineRemapDto);
        self::assertInstanceOf(EngineRemapUpdateDto::class, $engineRemapDto);
        self::assertSame('libelle test', $engineRemapDto->libelle());
        self::assertSame('description test', $engineRemapDto->description());
    }
}
