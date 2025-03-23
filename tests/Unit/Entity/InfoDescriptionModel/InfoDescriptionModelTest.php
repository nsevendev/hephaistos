<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Entity\InfoDescriptionModel;

use DateTimeImmutable;
use Heph\Entity\InfoDescriptionModel\InfoDescriptionModel;
use Heph\Entity\Shared\ValueObject\DescriptionValueObject;
use Heph\Entity\Shared\ValueObject\LibelleValueObject;
use Heph\Tests\Faker\Entity\InfoDescriptionModel\InfoDescriptionModelFaker;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[
    CoversClass(InfoDescriptionModel::class),
    CoversClass(LibelleValueObject::class),
    CoversClass(DescriptionValueObject::class),
]
class InfoDescriptionModelTest extends HephUnitTestCase
{
    public function testEntityInitialization(): void
    {
        $libelle = 'libelle test';
        $description = 'description test';
        $InfoDecriptionModel = InfoDescriptionModelFaker::new();

        self::assertSame($libelle, $InfoDecriptionModel->libelle()->value());
        self::assertSame($description, $InfoDecriptionModel->description()->value());
        self::assertSame($libelle, $InfoDecriptionModel->libelle()->jsonSerialize());
        self::assertSame($description, $InfoDecriptionModel->description()->jsonSerialize());
        self::assertSame($libelle, (string) $InfoDecriptionModel->libelle());
        self::assertSame($description, (string) $InfoDecriptionModel->description());
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
        $InfoDescriptionModel->setLibelle(new LibelleValueObject($newLibelleUpdated));

        self::assertSame($newLibelleUpdated, $InfoDescriptionModel->libelle()->value());

        $newDescriptionUpdated = 'set test';
        $InfoDescriptionModel->setDescription(new DescriptionValueObject($newDescriptionUpdated));

        self::assertSame($newDescriptionUpdated, $InfoDescriptionModel->description()->value());
    }
}
