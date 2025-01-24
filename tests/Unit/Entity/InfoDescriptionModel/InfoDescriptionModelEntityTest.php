<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Entity\InfoDescriptionModel;

use DateTimeImmutable;
use Heph\Entity\InfoDescriptionModel\InfoDescriptionModelEntity;
use Heph\Entity\Shared\Type\Uid;
use Heph\Infrastructure\Doctrine\Type\UidType;
use Heph\Tests\Faker\Entity\InfoDescriptionModel\InfoDescriptionModelEntityFaker;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(InfoDescriptionModelEntity::class), CoversClass(Uid::class), CoversClass(UidType::class)]
class InfoDescriptionModelEntityTest extends HephUnitTestCase
{
    public function testEntityInitialization(): void
    {
        $InfoDecriptionModelEntity = InfoDescriptionModelEntityFaker::new();

        self::assertInstanceOf(InfoDescriptionModelEntity::class, $InfoDecriptionModelEntity);
        self::assertSame('libellé test', $InfoDecriptionModelEntity->libelle());
        self::assertSame('description test', $InfoDecriptionModelEntity->description());
        self::assertNotNull($InfoDecriptionModelEntity->id());
        self::assertNotNull($InfoDecriptionModelEntity->createdAt());
        self::assertNotNull($InfoDecriptionModelEntity->updatedAt());
    }

    public function testNewWithEmptyValues(): void
    {
        $InfoDescriptionModelEntity = InfoDescriptionModelEntityFaker::newWithNEmptyValues();

        self::assertInstanceOf(InfoDescriptionModelEntity::class, $InfoDescriptionModelEntity);
        self::assertSame('', $InfoDescriptionModelEntity->libelle());
        self::assertSame('', $InfoDescriptionModelEntity->description());
        self::assertNotNull($InfoDescriptionModelEntity->id());
        self::assertNotNull($InfoDescriptionModelEntity->createdAt());
        self::assertNotNull($InfoDescriptionModelEntity->updatedAt());

        $newDateUpdated = new DateTimeImmutable();
        $InfoDescriptionModelEntity->setUpdatedAt($newDateUpdated);

        self::assertSame($newDateUpdated, $InfoDescriptionModelEntity->updatedAt());

        $newLibelleUpdated = 'set test';
        $InfoDescriptionModelEntity->setLibelle($newLibelleUpdated);

        self::assertSame($newLibelleUpdated, $InfoDescriptionModelEntity->libelle());

        $newDescriptionUpdated = 'set test';
        $InfoDescriptionModelEntity->setDescription($newDescriptionUpdated);

        self::assertSame($newDescriptionUpdated, $InfoDescriptionModelEntity->description());
    }
}
