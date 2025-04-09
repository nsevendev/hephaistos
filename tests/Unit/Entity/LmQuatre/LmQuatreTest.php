<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Entity\LmQuatre;

use DateTimeImmutable;
use Heph\Entity\InfoDescriptionModel\InfoDescriptionModel;
use Heph\Entity\LmQuatre\LmQuatre;
use Heph\Entity\LmQuatre\ValueObject\LmQuatreAdresse;
use Heph\Entity\LmQuatre\ValueObject\LmQuatreEmail;
use Heph\Entity\LmQuatre\ValueObject\LmQuatreOwner;
use Heph\Entity\LmQuatre\ValueObject\LmQuatrePhoneNumber;
use Heph\Entity\Shared\ValueObject\DescriptionValueObject;
use Heph\Entity\Shared\ValueObject\LibelleValueObject;
use Heph\Tests\Faker\Entity\InfoDescriptionModel\InfoDescriptionModelFaker;
use Heph\Tests\Faker\Entity\LmQuatre\LmQuatreFaker;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[
    CoversClass(LmQuatre::class),
    CoversClass(InfoDescriptionModel::class),
    CoversClass(LibelleValueObject::class),
    CoversClass(DescriptionValueObject::class),
    CoversClass(LmQuatreAdresse::class),
    CoversClass(LmQuatreEmail::class),
    CoversClass(LmQuatreOwner::class),
    CoversClass(LmQuatrePhoneNumber::class),
]
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
        self::assertSame('Math', $LmQuatre->owner()->value());
        self::assertSame('33 rue du test', $LmQuatre->adresse()->value());
        self::assertSame('test@exemple.com', $LmQuatre->email()->value());
        self::assertSame('123456789', $LmQuatre->phoneNumber()->value());
        self::assertNotNull($LmQuatre->infoDescriptionModel());
    }

    public function testEntitySetters(): void
    {
        $LmQuatre = LmQuatreFaker::new();

        $newDateUpdated = new DateTimeImmutable();
        $LmQuatre->setUpdatedAt($newDateUpdated);

        self::assertSame($newDateUpdated, $LmQuatre->updatedAt());

        $newOwnerUpdate = 'Bob Marley';
        $LmQuatre->setOwner(LmQuatreOwner::fromValue($newOwnerUpdate));

        self::assertSame($newOwnerUpdate, $LmQuatre->Owner()->value());

        $newAdresseUpdate = '34 rue du test';
        $LmQuatre->setAdresse(LmQuatreAdresse::fromValue($newAdresseUpdate));

        self::assertSame($newAdresseUpdate, $LmQuatre->adresse()->value());

        $newEmailUpdate = 'test2@exemple.com';
        $LmQuatre->setEmail(LmQuatreEmail::fromValue($newEmailUpdate));

        self::assertSame($newEmailUpdate, $LmQuatre->email()->value());

        $newPhoneNumberUpdate = '84698759';
        $LmQuatre->setPhoneNumber(LmQuatrePhoneNumber::fromValue($newPhoneNumberUpdate));

        self::assertSame($newPhoneNumberUpdate, $LmQuatre->phoneNumber()->value());

        $newCompanyCreateDateUpdate = new DateTimeImmutable();
        $LmQuatre->setCompanyCreateDate($newCompanyCreateDateUpdate);

        self::assertSame($newCompanyCreateDateUpdate, $LmQuatre->companyCreateDate());

        $newInfoDescriptionModelUpdated = InfoDescriptionModelFaker::new();
        $LmQuatre->setInfoDescriptionModel($newInfoDescriptionModelUpdated);

        self::assertSame($newInfoDescriptionModelUpdated, $LmQuatre->infoDescriptionModel());
    }
}
