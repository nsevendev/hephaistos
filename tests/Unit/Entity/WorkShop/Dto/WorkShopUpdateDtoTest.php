<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Entity\WorkShop\Dto;

use Heph\Entity\InfoDescriptionModel\Dto\InfoDescriptionModelCreateDto;
use Heph\Entity\Shared\ValueObject\DescriptionValueObject;
use Heph\Entity\Shared\ValueObject\LibelleValueObject;
use Heph\Entity\WorkShop\Dto\WorkShopUpdateDto;
use Heph\Tests\Faker\Dto\WorkShop\WorkShopUpdateDtoFaker;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(WorkShopUpdateDto::class), CoversClass(DescriptionValueObject::class), CoversClass(LibelleValueObject::class), CoversClass(InfoDescriptionModelCreateDto::class)]
class WorkShopUpdateDtoTest extends HephUnitTestCase
{
    public function testWorkShopUpdateDto(): void
    {
        $infoDescriptionModel = InfoDescriptionModelCreateDto::new('libelle test', 'description test');
        $updateWorkShopDto = new WorkShopUpdateDto($infoDescriptionModel);

        self::assertNotNull($updateWorkShopDto);

        self::assertInstanceOf(WorkShopUpdateDto::class, $updateWorkShopDto);
    }

    public function testWorkShopUpdateDtoWithFaker(): void
    {
        $updateWorkShopDto = WorkShopUpdateDtoFaker::new();

        self::assertNotNull($updateWorkShopDto);
        self::assertInstanceOf(WorkShopUpdateDto::class, $updateWorkShopDto);
    }

    public function testWorkShopUpdateDtoWithFonctionNew(): void
    {
        $updateWorkShopDto = WorkShopUpdateDto::new('libelle test', 'description test');

        self::assertNotNull($updateWorkShopDto);
        self::assertInstanceOf(WorkShopUpdateDto::class, $updateWorkShopDto);
    }
}
