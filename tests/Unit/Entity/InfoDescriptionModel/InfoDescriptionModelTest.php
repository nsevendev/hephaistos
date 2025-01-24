<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Entity\InfoDescriptionModel;

use DateTimeImmutable;
use Heph\Entity\InfoDescriptionModel\InfoDescriptionModel;
use Heph\Entity\Shared\Type\Uid;
use Heph\Infrastructure\Doctrine\Type\UidType;
use Heph\Tests\Faker\Entity\InfoDescriptionModel\InfoDescriptionModelFaker;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(InfoDescriptionModel::class), CoversClass(Uid::class), CoversClass(UidType::class)]
class InfoDescriptionModelTest extends HephUnitTestCase
{
    public function testEntityInitialization(): void
    {
        $InfoDecriptionModel = InfoDescriptionModelFaker::new();

        self::assertInstanceOf(InfoDescriptionModel::class, $InfoDecriptionModel);
        self::assertSame('libellé test', $InfoDecriptionModel->libelle());
        self::assertSame('description test', $InfoDecriptionModel->description());
        self::assertNotNull($InfoDecriptionModel->id());
        self::assertNotNull($InfoDecriptionModel->createdAt());
        self::assertNotNull($InfoDecriptionModel->updatedAt());
    }

    public function testNewWithEmptyValues(): void
    {
        $InfoDescriptionModel = InfoDescriptionModelFaker::newWithNEmptyValues();

        self::assertInstanceOf(InfoDescriptionModel::class, $InfoDescriptionModel);
        self::assertSame('', $InfoDescriptionModel->libelle());
        self::assertSame('', $InfoDescriptionModel->description());
        self::assertNotNull($InfoDescriptionModel->id());
        self::assertNotNull($InfoDescriptionModel->createdAt());
        self::assertNotNull($InfoDescriptionModel->updatedAt());

        $newDateUpdated = new DateTimeImmutable();
        $InfoDescriptionModel->setUpdatedAt($newDateUpdated);

        self::assertSame($newDateUpdated, $InfoDescriptionModel->updatedAt());

        $newLibelleUpdated = 'set test';
        $InfoDescriptionModel->setLibelle($newLibelleUpdated);

        self::assertSame($newLibelleUpdated, $InfoDescriptionModel->libelle());

        $newDescriptionUpdated = 'set test';
        $InfoDescriptionModel->setDescription($newDescriptionUpdated);

        self::assertSame($newDescriptionUpdated, $InfoDescriptionModel->description());
    }
}
