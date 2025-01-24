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
        $entity = InfoDescriptionModelEntityFaker::new();

        self::assertInstanceOf(InfoDescriptionModelEntity::class, $entity);
        self::assertSame('libellé test', $entity->libelle());
        self::assertSame('description test', $entity->description());
        self::assertNotNull($entity->id());
        self::assertNotNull($entity->createdAt());
        self::assertNotNull($entity->updatedAt());
    }

    public function testNewWithNullValues(): void
    {
        $entity = InfoDescriptionModelEntityFaker::newWithNullValues();

        self::assertInstanceOf(InfoDescriptionModelEntity::class, $entity);
        self::assertSame('', $entity->libelle());
        self::assertSame('', $entity->description());
        self::assertNotNull($entity->id());
        self::assertNotNull($entity->createdAt());
        self::assertNotNull($entity->updatedAt());

        $newDateUpdated = new DateTimeImmutable();
        $entity->setUpdatedAt($newDateUpdated);
        self::assertSame($newDateUpdated, $entity->updatedAt());
    }
}
