<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Entity\CarSales;

use DateTimeImmutable;
use Heph\Entity\CarSales\CarSalesEntity;
use Heph\Entity\Shared\Type\Uid;
use Heph\Infrastructure\Doctrine\Type\UidType;
use Heph\Tests\Faker\Entity\CarSales\CarSalesEntityFaker;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(CarSalesEntity::class), CoversClass(Uid::class), CoversClass(UidType::class)]
class CarSalesEntityTest extends HephUnitTestCase
{
    public function testEntityInitialization(): void
    {
        $CarSalesEntity = CarSalesEntityFaker::new();

        self::assertInstanceOf(CarSalesEntity::class, $CarSalesEntity);
        self::assertNotNull($CarSalesEntity->id());
        self::assertInstanceOf(DateTimeImmutable::class, $CarSalesEntity->createdAt());
        self::assertInstanceOf(DateTimeImmutable::class, $CarSalesEntity->updatedAt());
        self::assertNotNull($CarSalesEntity->getInfoDescriptionModel());

        $newDateUpdated = new DateTimeImmutable();
        $CarSalesEntity->setUpdatedAt($newDateUpdated);

        self::assertSame($newDateUpdated, $CarSalesEntity->updatedAt());
    }
}
