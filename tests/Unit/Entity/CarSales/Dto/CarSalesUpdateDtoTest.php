<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Entity\CarSales\Dto;

use Heph\Entity\CarSales\Dto\CarSalesUpdateDto;
use Heph\Entity\InfoDescriptionModel\Dto\InfoDescriptionModelCreateDto;
use Heph\Entity\Shared\ValueObject\DescriptionValueObject;
use Heph\Entity\Shared\ValueObject\LibelleValueObject;
use Heph\Tests\Faker\Dto\CarSales\CarSalesUpdateDtoFaker;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(CarSalesUpdateDto::class), CoversClass(DescriptionValueObject::class), CoversClass(LibelleValueObject::class), CoversClass(InfoDescriptionModelCreateDto::class)]
class CarSalesUpdateDtoTest extends HephUnitTestCase
{
    public function testCarSalesUpdateDto(): void
    {
        $infoDescriptionModel = InfoDescriptionModelCreateDto::new('libelle test', 'description test');
        $updateCarSalesDto = new CarSalesUpdateDto($infoDescriptionModel);

        self::assertNotNull($updateCarSalesDto);

        self::assertInstanceOf(CarSalesUpdateDto::class, $updateCarSalesDto);
    }

    public function testCarSalesUpdateDtoWithFaker(): void
    {
        $updateCarSalesDto = CarSalesUpdateDtoFaker::new();

        self::assertNotNull($updateCarSalesDto);
        self::assertInstanceOf(CarSalesUpdateDto::class, $updateCarSalesDto);
    }

    public function testCarSalesUpdateDtoWithFonctionNew(): void
    {
        $updateCarSalesDto = CarSalesUpdateDto::new('libelle test', 'description test');

        self::assertNotNull($updateCarSalesDto);
        self::assertInstanceOf(CarSalesUpdateDto::class, $updateCarSalesDto);
    }
}
