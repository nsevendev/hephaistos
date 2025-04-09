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
use Heph\Infrastructure\ApiResponse\Exception\Error\Error;
use Heph\Entity\Shared\ValueObject\DescriptionValueObject;
use Heph\Entity\Shared\ValueObject\LibelleValueObject;
use Heph\Infrastructure\ApiResponse\Exception\Custom\LmQuatre\LmQuatreInvalidArgumentException;
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
    CoversClass(LmQuatreInvalidArgumentException::class),
    CoversClass(Error::class),
]
class LmQuatreTest extends HephUnitTestCase
{
    public function testEntityInitialization(): void
    {
        $owner = 'Math';
        $adresse = '33 rue du test';
        $email = 'test@exemple.com';
        $phoneNumber = '123456789';

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
        self::assertSame($owner, $LmQuatre->owner()->jsonSerialize());
        self::assertSame($adresse, $LmQuatre->adresse()->jsonSerialize());
        self::assertSame($email, $LmQuatre->email()->jsonSerialize());
        self::assertSame($phoneNumber, $LmQuatre->phoneNumber()->jsonSerialize());
        self::assertSame($owner, (string) $LmQuatre->owner());
        self::assertSame($adresse, (string) $LmQuatre->adresse());
        self::assertSame($email, (string) $LmQuatre->email());
        self::assertSame($phoneNumber, (string) $LmQuatre->phoneNumber());
        self::assertNotNull($LmQuatre->infoDescriptionModel());
    }

    /**
     * @throws LmQuatreInvalidArgumentException
     */
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

    /**
     * @throws LmQuatreInvalidArgumentException
     */
    public function testEntityWithOwnerMoreLonger(): void
    {
        $this->expectException(LmQuatreInvalidArgumentException::class);

        $lmQuatre = LmQuatreFaker::withOwnerMoreLonger();
    }

    /**
     * @throws LmQuatreInvalidArgumentException
     */
    public function testEntityWithOwnerEmpty(): void
    {
        $this->expectException(LmQuatreInvalidArgumentException::class);

        $lmQuatre = LmQuatreFaker::withOwnerEmpty();
    }

    /**
     * @throws LmQuatreInvalidArgumentException
     */
    public function testEntityWithAdresseMoreLonger(): void
    {
        $this->expectException(LmQuatreInvalidArgumentException::class);

        $lmQuatre = LmQuatreFaker::withAdresseMoreLonger();
    }

    /**
     * @throws LmQuatreInvalidArgumentException
     */
    public function testEntityWithAdresseEmpty(): void
    {
        $this->expectException(LmQuatreInvalidArgumentException::class);

        $lmQuatre = LmQuatreFaker::withAdresseEmpty();
    }

    /**
     * @throws LmQuatreInvalidArgumentException
     */
    public function testEntityWithPhoneNumberMoreLonger(): void
    {
        $this->expectException(LmQuatreInvalidArgumentException::class);

        $lmQuatre = LmQuatreFaker::withPhoneNumberMoreLonger();
    }

    /**
     * @throws LmQuatreInvalidArgumentException
     */
    public function testEntityWithPhoneNumberEmpty(): void
    {
        $this->expectException(LmQuatreInvalidArgumentException::class);

        $lmQuatre = LmQuatreFaker::withPhoneNumberEmpty();
    }

    /**
     * @throws LmQuatreInvalidArgumentException
     */
    public function testEntityWithEmailInvalid(): void
    {
        $this->expectException(LmQuatreInvalidArgumentException::class);

        $lmQuatre = LmQuatreFaker::withEmailInvalid();
    }

    /**
     * @throws LmQuatreInvalidArgumentException
     */
    public function testEntityWithEmailEmpty(): void
    {
        $this->expectException(LmQuatreInvalidArgumentException::class);

        $lmQuatre = LmQuatreFaker::withEmailEmpty();
    }

    /**
     * @throws LmQuatreInvalidArgumentException
     */
    public function testEntityWithEmailMoreLonger(): void
    {
        $this->expectException(LmQuatreInvalidArgumentException::class);

        $lmQuatre = LmQuatreFaker::withEmailMoreLonger();
    }
}
