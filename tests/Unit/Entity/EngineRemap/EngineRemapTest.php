<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Entity\EngineRemap;

use DateTimeImmutable;
use Heph\Entity\EngineRemap\EngineRemap;
use Heph\Entity\InfoDescriptionModel\InfoDescriptionModel;
use Heph\Tests\Faker\Entity\EngineRemap\EngineRemapFaker;
use Heph\Tests\Faker\Entity\InfoDescriptionModel\InfoDescriptionModelFaker;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[
    CoversClass(EngineRemap::class),
    CoversClass(InfoDescriptionModel::class),
]
class EngineRemapTest extends HephUnitTestCase
{
    public function testEntityInitialization(): void
    {
        $EngineRemap = EngineRemapFaker::new();

        self::assertInstanceOf(EngineRemap::class, $EngineRemap);
        self::assertNotNull($EngineRemap->id());
        self::assertInstanceOf(DateTimeImmutable::class, $EngineRemap->createdAt());
        self::assertInstanceOf(DateTimeImmutable::class, $EngineRemap->updatedAt());
        self::assertNotNull($EngineRemap->infoDescriptionModel());

        $newDateUpdated = new DateTimeImmutable();
        $EngineRemap->setUpdatedAt($newDateUpdated);

        self::assertSame($newDateUpdated, $EngineRemap->updatedAt());

        $newInfoDescriptionModelUpdated = InfoDescriptionModelFaker::new();
        $EngineRemap->setInfoDescriptionModel($newInfoDescriptionModelUpdated);

        self::assertSame($newInfoDescriptionModelUpdated, $EngineRemap->infoDescriptionModel());
    }

    public function testEntitySetters(): void
    {
        $EngineRemap = EngineRemapFaker::new();

        $newDateUpdated = new DateTimeImmutable();
        $EngineRemap->setUpdatedAt($newDateUpdated);

        self::assertSame($newDateUpdated, $EngineRemap->updatedAt());

        $newInfoDescriptionModelUpdated = InfoDescriptionModelFaker::new();
        $EngineRemap->setInfoDescriptionModel($newInfoDescriptionModelUpdated);

        self::assertSame($newInfoDescriptionModelUpdated, $EngineRemap->infoDescriptionModel());
    }
}
