<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Entity\CarSales;

use DateTimeImmutable;
use Heph\Entity\CarSales\CarSales;
use Heph\Entity\InfoDescriptionModel\InfoDescriptionModel;
use Heph\Entity\Shared\ValueObject\DescriptionValueObject;
use Heph\Entity\Shared\ValueObject\LibelleValueObject;
use Heph\Tests\Faker\Entity\CarSales\CarSalesFaker;
use Heph\Tests\Faker\Entity\InfoDescriptionModel\InfoDescriptionModelFaker;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[
    CoversClass(CarSales::class),
    CoversClass(LibelleValueObject::class),
    CoversClass(DescriptionValueObject::class),
    CoversClass(InfoDescriptionModel::class)
]
class CarSalesTest extends HephUnitTestCase
{
    public function testEntityInitialization(): void
    {
        $CarSales = CarSalesFaker::new();

        self::assertInstanceOf(CarSales::class, $CarSales);
        self::assertNotNull($CarSales->id());
        self::assertInstanceOf(DateTimeImmutable::class, $CarSales->createdAt());
        self::assertInstanceOf(DateTimeImmutable::class, $CarSales->updatedAt());
        self::assertNotNull($CarSales->infoDescriptionModel());

        $newDateUpdated = new DateTimeImmutable();
        $CarSales->setUpdatedAt($newDateUpdated);

        self::assertSame($newDateUpdated, $CarSales->updatedAt());

        $newInfoDescriptionModelUpdated = InfoDescriptionModelFaker::new();
        $CarSales->setInfoDescriptionModel($newInfoDescriptionModelUpdated);

        self::assertSame($newInfoDescriptionModelUpdated, $CarSales->infoDescriptionModel());
    }

    public function testEntitySetters(): void
    {
        $CarSales = CarSalesFaker::new();

        $newDateUpdated = new DateTimeImmutable();
        $CarSales->setUpdatedAt($newDateUpdated);

        self::assertSame($newDateUpdated, $CarSales->updatedAt());

        $newInfoDescriptionModelUpdated = InfoDescriptionModelFaker::new();
        $CarSales->setInfoDescriptionModel($newInfoDescriptionModelUpdated);

        self::assertSame($newInfoDescriptionModelUpdated, $CarSales->infoDescriptionModel());
    }
}
