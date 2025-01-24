<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Entity\LmQuatre;

use DateTimeImmutable;
use Heph\Entity\LmQuatre\LmQuatreEntity;
use Heph\Entity\Shared\Type\Uid;
use Heph\Infrastructure\Doctrine\Type\UidType;
use Heph\Tests\Faker\Entity\LmQuatre\LmQuatreEntityFaker;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(LmQuatreEntity::class), CoversClass(Uid::class), CoversClass(UidType::class)]
class LmQuatreEntityTest extends HephUnitTestCase
{
    public function testEntityInitialization(): void
    {
        $LmQuatreEntity = LmQuatreEntityFaker::new();

        self::assertInstanceOf(LmQuatreEntity::class, $LmQuatreEntity);
        self::assertNotNull($LmQuatreEntity->id());
        self::assertInstanceOf(DateTimeImmutable::class, $LmQuatreEntity->createdAt());
        self::assertInstanceOf(DateTimeImmutable::class, $LmQuatreEntity->updatedAt());
        self::assertInstanceOf(DateTimeImmutable::class, $LmQuatreEntity->companyCreateDate());
        self::assertSame('Math', $LmQuatreEntity->owner());
        self::assertSame('33 rue du test', $LmQuatreEntity->adresse());
        self::assertSame('test@exemple.com', $LmQuatreEntity->email());
        self::assertSame(123456789, $LmQuatreEntity->phoneNumber());
        self::assertNotNull($LmQuatreEntity->infoDescriptionModel());

        $newDateUpdated = new DateTimeImmutable();
        $LmQuatreEntity->setUpdatedAt($newDateUpdated);

        self::assertSame($newDateUpdated, $LmQuatreEntity->updatedAt());

        $newAdresseUpdate = "34 rue du test";
        $LmQuatreEntity->setadresse($newAdresseUpdate);

        self::assertSame($newAdresseUpdate, $LmQuatreEntity->adresse());

        $newEmailUpdate = 'test2@exemple.com';
        $LmQuatreEntity->setEmail($newEmailUpdate);

        self::assertSame($newEmailUpdate, $LmQuatreEntity->email());

        $newPhoneNumberUpdate = 84698759;
        $LmQuatreEntity->setphoneNumber($newPhoneNumberUpdate);

        self::assertSame($newPhoneNumberUpdate, $LmQuatreEntity->phoneNumber());
    }

    public function testEntityWithNullValues(): void
    {
        $entity = LmQuatreEntityFaker::newWithNullValues();

        self::assertInstanceOf(LmQuatreEntity::class, $entity);
        self::assertNotNull($entity->id());
        self::assertInstanceOf(DateTimeImmutable::class, $entity->createdAt());
        self::assertInstanceOf(DateTimeImmutable::class, $entity->updatedAt());
        self::assertNull($entity->owner());
        self::assertNull($entity->adresse());
        self::assertNull($entity->email());
        self::assertNull($entity->phoneNumber());
        self::assertNull($entity->companyCreateDate());
        self::assertNotNull($entity->infoDescriptionModel());

        $newDateUpdated = new DateTimeImmutable();
        $entity->setUpdatedAt($newDateUpdated);

        self::assertSame($newDateUpdated, $entity->updatedAt());
    }
}