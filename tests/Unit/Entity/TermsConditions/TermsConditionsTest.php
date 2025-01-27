<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Entity\TermsConditions;

use DateTimeImmutable;
use Heph\Entity\InfoDescriptionModel\InfoDescriptionModel;
use Heph\Entity\TermsConditions\TermsConditions;
use Heph\Tests\Faker\Entity\InfoDescriptionModel\InfoDescriptionModelFaker;
use Heph\Tests\Faker\Entity\TermsConditions\TermsConditionsFaker;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(TermsConditions::class), CoversClass(InfoDescriptionModel::class)]
class TermsConditionsTest extends HephUnitTestCase
{
    public function testEntityInitialization(): void
    {
        $termsConditions = TermsConditionsFaker::new();

        self::assertInstanceOf(TermsConditions::class, $termsConditions);
        self::assertNotNull($termsConditions->id());
        self::assertInstanceOf(DateTimeImmutable::class, $termsConditions->createdAt());
        self::assertInstanceOf(DateTimeImmutable::class, $termsConditions->updatedAt());
        self::assertNotNull($termsConditions->infoDescriptionModel());
    }

    public function testEntitySetters(): void
    {
        $termsConditions = TermsConditionsFaker::new();

        $newDateUpdated = new DateTimeImmutable();
        $termsConditions->setUpdatedAt($newDateUpdated);

        self::assertSame($newDateUpdated, $termsConditions->updatedAt());

        $newInfoDescriptionModelUpdated = InfoDescriptionModelFaker::new();
        $termsConditions->setInfoDescriptionModel($newInfoDescriptionModelUpdated);

        self::assertSame($newInfoDescriptionModelUpdated, $termsConditions->infoDescriptionModel());
    }
}
