<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Entity\LmQuatre\Dto;

use Heph\Entity\InfoDescriptionModel\Dto\InfoDescriptionModelCreateDto;
use Heph\Entity\LmQuatre\Dto\LmQuatreCreateDto;
use Heph\Entity\Shared\ValueObject\DescriptionValueObject;
use Heph\Entity\Shared\ValueObject\LibelleValueObject;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(LmQuatreCreateDto::class), CoversClass(InfoDescriptionModelCreateDto::class), CoversClass(LibelleValueObject::class), CoversClass(DescriptionValueObject::class),]
class LmQuatreCreateDtoTest extends HephUnitTestCase
{
    public function testLmQuatreCreateDto(): void
    {
        $infoDescriptionModel = InfoDescriptionModelCreateDto::new('libelle test', 'description test');
        $lmQuatreDto = new LmQuatreCreateDto($infoDescriptionModel, 'Math', 'adresse faker', 'test@test.com', '123456789');

        self::assertNotNull($lmQuatreDto);
        self::assertInstanceOf(LmQuatreCreateDto::class, $lmQuatreDto);
        self::assertInstanceOf(InfoDescriptionModelCreateDto::class, $lmQuatreDto->infoDescriptionModel);
    }

    public function testLmQuatreCreateDtoWithFunctionNew(): void
    {
        $lmQuatreDto = LmQuatreCreateDto::new('libelle test', 'description test', 'Math', 'adresse faker', 'test@test.com', '123456789');

        self::assertNotNull($lmQuatreDto);
        self::assertInstanceOf(LmQuatreCreateDto::class, $lmQuatreDto);
        self::assertInstanceOf(InfoDescriptionModelCreateDto::class, $lmQuatreDto->infoDescriptionModel);
    }
}
