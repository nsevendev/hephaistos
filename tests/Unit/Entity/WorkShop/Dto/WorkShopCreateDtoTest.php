<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Entity\WorkShop\Dto;

use Heph\Entity\InfoDescriptionModel\Dto\InfoDescriptionModelCreateDto;
use Heph\Entity\Shared\ValueObject\DescriptionValueObject;
use Heph\Entity\Shared\ValueObject\LibelleValueObject;
use Heph\Entity\WorkShop\Dto\WorkShopCreateDto;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(WorkShopCreateDto::class), CoversClass(InfoDescriptionModelCreateDto::class), CoversClass(LibelleValueObject::class), CoversClass(DescriptionValueObject::class),]
class WorkShopCreateDtoTest extends HephUnitTestCase
{
    public function testWorkShopCreateDto(): void
    {
        $infoDescriptionModel = InfoDescriptionModelCreateDto::new('libelle test', 'description test');
        $workShopDto = new WorkShopCreateDto($infoDescriptionModel);

        self::assertNotNull($workShopDto);
        self::assertInstanceOf(WorkShopCreateDto::class, $workShopDto);
        self::assertInstanceOf(InfoDescriptionModelCreateDto::class, $workShopDto->infoDescriptionModel);
    }

    public function testWorkShopCreateDtoWithFunctionNew(): void
    {
        $workShopDto = WorkShopCreateDto::new('libelle test', 'description test');

        self::assertNotNull($workShopDto);
        self::assertInstanceOf(WorkShopCreateDto::class, $workShopDto);
        self::assertInstanceOf(InfoDescriptionModelCreateDto::class, $workShopDto->infoDescriptionModel);
    }
}
