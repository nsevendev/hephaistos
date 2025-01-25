<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Entity\WorkShop;

use DateTimeImmutable;
use Heph\Entity\InfoDescriptionModel\InfoDescriptionModel;
use Heph\Entity\Shared\Type\Uid;
use Heph\Entity\WorkShop\WorkShop;
use Heph\Infrastructure\Doctrine\Type\UidType;
use Heph\Tests\Faker\Entity\InfoDescriptionModel\InfoDescriptionModelFaker;
use Heph\Tests\Faker\Entity\WorkShop\WorkShopFaker;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(WorkShop::class), CoversClass(InfoDescriptionModel::class), CoversClass(Uid::class), CoversClass(UidType::class)]
class WorkShopTest extends HephUnitTestCase
{
    public function testEntityInitialization(): void
    {
        $WorkShop = WorkShopFaker::new();

        self::assertInstanceOf(WorkShop::class, $WorkShop);
        self::assertNotNull($WorkShop->id());
        self::assertInstanceOf(DateTimeImmutable::class, $WorkShop->createdAt());
        self::assertInstanceOf(DateTimeImmutable::class, $WorkShop->updatedAt());
        self::assertNotNull($WorkShop->infoDescriptionModel());
    }

    public function testEntitySetters(): void
    {
        $WorkShop = WorkShopFaker::new();

        $newDateUpdated = new DateTimeImmutable();
        $WorkShop->setUpdatedAt($newDateUpdated);

        self::assertSame($newDateUpdated, $WorkShop->updatedAt());

        $newInfoDescriptionModelUpdated = InfoDescriptionModelFaker::new();
        $WorkShop->setInfoDescriptionModel($newInfoDescriptionModelUpdated);

        self::assertSame($newInfoDescriptionModelUpdated, $WorkShop->infoDescriptionModel());
    }
}
