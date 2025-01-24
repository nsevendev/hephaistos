<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Entity\WorkShop;

use DateTimeImmutable;
use Heph\Entity\Shared\Type\Uid;
use Heph\Entity\WorkShop\WorkShopEntity;
use Heph\Infrastructure\Doctrine\Type\UidType;
use Heph\Tests\Faker\Entity\WorkShop\WorkShopEntityFaker;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(WorkShopEntity::class), CoversClass(Uid::class), CoversClass(UidType::class)]
class WorkShopEntityTest extends HephUnitTestCase
{
    public function testEntityInitialization(): void
    {
        $WorkShopEntity = WorkShopEntityFaker::new();

        self::assertInstanceOf(WorkShopEntity::class, $WorkShopEntity);
        self::assertNotNull($WorkShopEntity->id());
        self::assertInstanceOf(DateTimeImmutable::class, $WorkShopEntity->createdAt());
        self::assertInstanceOf(DateTimeImmutable::class, $WorkShopEntity->updatedAt());
        self::assertNotNull($WorkShopEntity->getInfoDescriptionModel());

        $newDateUpdated = new DateTimeImmutable();
        $WorkShopEntity->setUpdatedAt($newDateUpdated);

        self::assertSame($newDateUpdated, $WorkShopEntity->updatedAt());
    }
}
