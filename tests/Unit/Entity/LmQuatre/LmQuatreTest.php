<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Entity\LmQuatre;

use DateTimeImmutable;
use Heph\Entity\InfoDescriptionModel\InfoDescriptionModel;
use Heph\Entity\LmQuatre\LmQuatre;
use Heph\Tests\Faker\Entity\InfoDescriptionModel\InfoDescriptionModelFaker;
use Heph\Tests\Faker\Entity\LmQuatre\LmQuatreFaker;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(LmQuatre::class), CoversClass(InfoDescriptionModel::class)]
class LmQuatreTest extends HephUnitTestCase
{
    public function testEntityInitialization(): void
    {
        $LmQuatre = LmQuatreFaker::new();

        self::assertInstanceOf(LmQuatre::class, $LmQuatre);
        self::assertNotNull($LmQuatre->id());
        self::assertInstanceOf(DateTimeImmutable::class, $LmQuatre->createdAt());
        self::assertInstanceOf(DateTimeImmutable::class, $LmQuatre->updatedAt());
        self::assertInstanceOf(DateTimeImmutable::class, $LmQuatre->companyCreateDate());
        self::assertSame('Math', $LmQuatre->owner());
        self::assertSame('33 rue du test', $LmQuatre->adresse());
        self::assertSame('test@exemple.com', $LmQuatre->email());
        self::assertSame('123456789', $LmQuatre->phoneNumber());
        self::assertNotNull($LmQuatre->infoDescriptionModel());
    }

    public function testEntitySetters(): void
    {
        $LmQuatre = LmQuatreFaker::new();

        $newDateUpdated = new DateTimeImmutable();
        $LmQuatre->setUpdatedAt($newDateUpdated);

        self::assertSame($newDateUpdated, $LmQuatre->updatedAt());

        $newOwnerUpdate = 'Bob Marley';
        $LmQuatre->setOwner($newOwnerUpdate);

        self::assertSame($newOwnerUpdate, $LmQuatre->Owner());

        $newAdresseUpdate = '34 rue du test';
        $LmQuatre->setAdresse($newAdresseUpdate);

        self::assertSame($newAdresseUpdate, $LmQuatre->adresse());

        $newEmailUpdate = 'test2@exemple.com';
        $LmQuatre->setEmail($newEmailUpdate);

        self::assertSame($newEmailUpdate, $LmQuatre->email());

        $newPhoneNumberUpdate = '84698759';
        $LmQuatre->setPhoneNumber($newPhoneNumberUpdate);

        self::assertSame($newPhoneNumberUpdate, $LmQuatre->phoneNumber());

        $newCompanyCreateDateUpdate = new DateTimeImmutable();
        $LmQuatre->setCompanyCreateDate($newCompanyCreateDateUpdate);

        self::assertSame($newCompanyCreateDateUpdate, $LmQuatre->companyCreateDate());

        $newInfoDescriptionModelUpdated = InfoDescriptionModelFaker::new();
        $LmQuatre->setInfoDescriptionModel($newInfoDescriptionModelUpdated);

        self::assertSame($newInfoDescriptionModelUpdated, $LmQuatre->infoDescriptionModel());
    }
}
