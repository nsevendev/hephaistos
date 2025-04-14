<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Entity\CarSales\Dto;

use Heph\Entity\CarSales\Dto\CarSalesCreateDto;
use Heph\Entity\InfoDescriptionModel\Dto\InfoDescriptionModelCreateDto;
use Heph\Entity\Shared\ValueObject\DescriptionValueObject;
use Heph\Entity\Shared\ValueObject\LibelleValueObject;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(CarSalesCreateDto::class), CoversClass(InfoDescriptionModelCreateDto::class), CoversClass(LibelleValueObject::class), CoversClass(DescriptionValueObject::class),]
class CarSalesCreateDtoTest extends HephUnitTestCase
{
    public function testCarSalesCreateDto(): void
    {
        $infoDescriptionModel = InfoDescriptionModelCreateDto::new('libelle test', 'description test');
        $carSalesDto = new CarSalesCreateDto($infoDescriptionModel);

        self::assertNotNull($carSalesDto);
        self::assertInstanceOf(CarSalesCreateDto::class, $carSalesDto);
        self::assertInstanceOf(InfoDescriptionModelCreateDto::class, $carSalesDto->infoDescriptionModel);
    }

    public function testCarSalesCreateDtoWithFunctionNew(): void
    {
        $carSalesDto = CarSalesCreateDto::new('libelle test', 'description test');

        self::assertNotNull($carSalesDto);
        self::assertInstanceOf(CarSalesCreateDto::class, $carSalesDto);
        self::assertInstanceOf(InfoDescriptionModelCreateDto::class, $carSalesDto->infoDescriptionModel);
    }
}
