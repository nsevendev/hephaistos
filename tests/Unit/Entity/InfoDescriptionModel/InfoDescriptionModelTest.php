<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Entity\InfoDescriptionModel;

use DateTimeImmutable;
use Heph\Entity\InfoDescriptionModel\InfoDescriptionModel;
use Heph\Tests\Faker\Entity\InfoDescriptionModel\InfoDescriptionModelFaker;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(InfoDescriptionModel::class)]
class InfoDescriptionModelTest extends HephUnitTestCase
{
    public function testEntityInitialization(): void
    {
        $InfoDecriptionModel = InfoDescriptionModelFaker::new();

        self::assertInstanceOf(InfoDescriptionModel::class, $InfoDecriptionModel);
        self::assertSame('libelle test', $InfoDecriptionModel->libelle());
        self::assertSame('description test', $InfoDecriptionModel->description());
        self::assertNotNull($InfoDecriptionModel->id());
        self::assertNotNull($InfoDecriptionModel->createdAt());
        self::assertNotNull($InfoDecriptionModel->updatedAt());
    }

    public function testEntitySetters(): void
    {
        $InfoDescriptionModel = InfoDescriptionModelFaker::new();

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
