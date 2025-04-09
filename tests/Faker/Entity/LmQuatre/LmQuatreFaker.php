<?php

declare(strict_types=1);

namespace Heph\Tests\Faker\Entity\LmQuatre;

use DateTimeImmutable;
use Heph\Entity\LmQuatre\LmQuatre;
use Heph\Entity\LmQuatre\ValueObject\LmQuatreAdresse;
use Heph\Entity\LmQuatre\ValueObject\LmQuatreEmail;
use Heph\Entity\LmQuatre\ValueObject\LmQuatreOwner;
use Heph\Entity\LmQuatre\ValueObject\LmQuatrePhoneNumber;
use Heph\Infrastructure\ApiResponse\Exception\Custom\LmQuatre\LmQuatreInvalidArgumentException;
use Heph\Tests\Faker\Entity\InfoDescriptionModel\InfoDescriptionModelFaker;

final class LmQuatreFaker
{
    /**
     * @throws LmQuatreInvalidArgumentException
     */
    public static function new(): LmQuatre
    {
        $infoDescriptionModel = InfoDescriptionModelFaker::new();

        return new LmQuatre(
            infoDescriptionModel: $infoDescriptionModel,
            owner: LmQuatreOwner::fromValue('Math'),
            adresse: LmQuatreAdresse::fromValue('33 rue du test'),
            email: LmQuatreEmail::fromValue('test@exemple.com'),
            phoneNumber: LmQuatrePhoneNumber::fromValue('123456789'),
            companyCreateDate: new DateTimeImmutable('2000-03-31')
        );
    }

    /**
     * @throws LmQuatreInvalidArgumentException
     */
    public static function withOwnerMoreLonger(): LmQuatre
    {
        $infoDescriptionModel = InfoDescriptionModelFaker::new();

        return new LmQuatre(
            infoDescriptionModel: $infoDescriptionModel,
            owner: LmQuatreOwner::fromValue('withOwnerMoreLongerwithOwnerMoreLongerwithOwnerMoreLongerwithOwnerMoreLongerwithOwnerMoreLongerwithOwnerMoreLongerwithOwnerMoreLongerwithOwnerMoreLongerwithOwnerMoreLongerwithOwnerMoreLongerwithOwnerMoreLongerwithOwnerMoreLongerwithOwnerMoreLongerwithOwnerMoreLongerwithOwnerMoreLongerwithOwnerMoreLongerwithOwnerMoreLongerwithOwnerMoreLongerwithOwnerMoreLongerwithOwnerMoreLongerwithOwnerMoreLongerwithOwnerMoreLongerwithOwnerMoreLongerwithOwnerMoreLongerwithOwnerMoreLongerwithOwnerMoreLongerwithOwnerMoreLongerwithOwnerMoreLonger'),
            adresse: LmQuatreAdresse::fromValue('33 rue du test'),
            email: LmQuatreEmail::fromValue('test@exemple.com'),
            phoneNumber: LmQuatrePhoneNumber::fromValue('123456789'),
            companyCreateDate: new DateTimeImmutable('2000-03-31')
        );
    }

    /**
     * @throws LmQuatreInvalidArgumentException
     */
    public static function withOwnerEmpty(): LmQuatre
    {
        $infoDescriptionModel = InfoDescriptionModelFaker::new();

        return new LmQuatre(
            infoDescriptionModel: $infoDescriptionModel,
            owner: LmQuatreOwner::fromValue(''),
            adresse: LmQuatreAdresse::fromValue('33 rue du test'),
            email: LmQuatreEmail::fromValue('test@exemple.com'),
            phoneNumber: LmQuatrePhoneNumber::fromValue('123456789'),
            companyCreateDate: new DateTimeImmutable('2000-03-31')
        );
    }

    /**
     * @throws LmQuatreInvalidArgumentException
     */
    public static function withAdresseMoreLonger(): LmQuatre
    {
        $infoDescriptionModel = InfoDescriptionModelFaker::new();

        return new LmQuatre(
            infoDescriptionModel: $infoDescriptionModel,
            owner: LmQuatreOwner::fromValue('Math'),
            adresse: LmQuatreAdresse::fromValue('withAdresseMoreLongerwithAdresseMoreLongerwithAdresseMoreLongerwithAdresseMoreLongerwithAdresseMoreLongerwithAdresseMoreLongerwithAdresseMoreLongerwithAdresseMoreLongerwithAdresseMoreLongerwithAdresseMoreLongerwithAdresseMoreLongerwithAdresseMoreLongerwithAdresseMoreLongerwithAdresseMoreLongerwithAdresseMoreLongerwithAdresseMoreLongerwithAdresseMoreLongerwithAdresseMoreLongerwithAdresseMoreLongerwithAdresseMoreLongerwithAdresseMoreLongerwithAdresseMoreLongerwithAdresseMoreLongerwithAdresseMoreLongerwithAdresseMoreLongerwithAdresseMoreLongerwithAdresseMoreLongerwithAdresseMoreLongerwithAdresseMoreLongerwithAdresseMoreLongerwithAdresseMoreLongerwithAdresseMoreLongerwithAdresseMoreLongerwithAdresseMoreLongerwithAdresseMoreLongerwithAdresseMoreLongerwithAdresseMoreLongerwithAdresseMoreLongerwithAdresseMoreLongerwithAdresseMoreLongerwithAdresseMoreLongerwithAdresseMoreLongerwithAdresseMoreLongerwithAdresseMoreLongerwithAdresseMoreLongerwithAdresseMoreLongerwithAdresseMoreLongerwithAdresseMoreLongerwithAdresseMoreLongerwithAdresseMoreLongerwithAdresseMoreLongerwithAdresseMoreLongerwithAdresseMoreLongerwithAdresseMoreLongerwithAdresseMoreLongerwithAdresseMoreLongerwithAdresseMoreLongerwithAdresseMoreLongerwithAdresseMoreLongerwithAdresseMoreLongerwithAdresseMoreLongerwithAdresseMoreLongerwithAdresseMoreLongerwithAdresseMoreLongerwithAdresseMoreLongerwithAdresseMoreLongerwithAdresseMoreLongerwithAdresseMoreLonger'),
            email: LmQuatreEmail::fromValue('test@exemple.com'),
            phoneNumber: LmQuatrePhoneNumber::fromValue('123456789'),
            companyCreateDate: new DateTimeImmutable('2000-03-31')
        );
    }

    /**
     * @throws LmQuatreInvalidArgumentException
     */
    public static function withAdresseEmpty(): LmQuatre
    {
        $infoDescriptionModel = InfoDescriptionModelFaker::new();

        return new LmQuatre(
            infoDescriptionModel: $infoDescriptionModel,
            owner: LmQuatreOwner::fromValue('Math'),
            adresse: LmQuatreAdresse::fromValue(''),
            email: LmQuatreEmail::fromValue('test@exemple.com'),
            phoneNumber: LmQuatrePhoneNumber::fromValue('123456789'),
            companyCreateDate: new DateTimeImmutable('2000-03-31')
        );
    }

    /**
     * @throws LmQuatreInvalidArgumentException
     */
    public static function withEmailInvalid(): LmQuatre
    {
        $infoDescriptionModel = InfoDescriptionModelFaker::new();

        return new LmQuatre(
            infoDescriptionModel: $infoDescriptionModel,
            owner: LmQuatreOwner::fromValue('Math'),
            adresse: LmQuatreAdresse::fromValue('33 rue du test'),
            email: LmQuatreEmail::fromValue('testexemple.com'),
            phoneNumber: LmQuatrePhoneNumber::fromValue('123456789'),
            companyCreateDate: new DateTimeImmutable('2000-03-31')
        );
    }

    /**
     * @throws LmQuatreInvalidArgumentException
     */
    public static function withEmailMoreLonger(): LmQuatre
    {
        $infoDescriptionModel = InfoDescriptionModelFaker::new();

        return new LmQuatre(
            infoDescriptionModel: $infoDescriptionModel,
            owner: LmQuatreOwner::fromValue('Math'),
            adresse: LmQuatreAdresse::fromValue('33 rue du test'),
            email: LmQuatreEmail::fromValue('test@exemple.comtest@exemple.comtest@exemple.comtest@exemple.comtest@exemple.comtest@exemple.comtest@exemple.comtest@exemple.comtest@exemple.comtest@exemple.comtest@exemple.comtest@exemple.comtest@exemple.comtest@exemple.comtest@exemple.comtest@exemple.comtest@exemple.comtest@exemple.comtest@exemple.comtest@exemple.comtest@exemple.comtest@exemple.comtest@exemple.comtest@exemple.comtest@exemple.comtest@exemple.comtest@exemple.comtest@exemple.comtest@exemple.com'),
            phoneNumber: LmQuatrePhoneNumber::fromValue('123456789'),
            companyCreateDate: new DateTimeImmutable('2000-03-31')
        );
    }

    /**
     * @throws LmQuatreInvalidArgumentException
     */
    public static function withEmailEmpty(): LmQuatre
    {
        $infoDescriptionModel = InfoDescriptionModelFaker::new();

        return new LmQuatre(
            infoDescriptionModel: $infoDescriptionModel,
            owner: LmQuatreOwner::fromValue('Math'),
            adresse: LmQuatreAdresse::fromValue('33 rue du test'),
            email: LmQuatreEmail::fromValue(''),
            phoneNumber: LmQuatrePhoneNumber::fromValue('123456789'),
            companyCreateDate: new DateTimeImmutable('2000-03-31')
        );
    }

    /**
     * @throws LmQuatreInvalidArgumentException
     */
    public static function withPhoneNumberMoreLonger(): LmQuatre
    {
        $infoDescriptionModel = InfoDescriptionModelFaker::new();

        return new LmQuatre(
            infoDescriptionModel: $infoDescriptionModel,
            owner: LmQuatreOwner::fromValue('Math'),
            adresse: LmQuatreAdresse::fromValue('33 rue du test'),
            email: LmQuatreEmail::fromValue('test@exemple.com'),
            phoneNumber: LmQuatrePhoneNumber::fromValue('123456789123456789123456789123456789123456789123456789123456789123456789123456789123456789123456789123456789123456789123456789123456789123456789123456789123456789123456789123456789123456789123456789123456789123456789123456789123456789123456789123456789123456789'),
            companyCreateDate: new DateTimeImmutable('2000-03-31')
        );
    }

    /**
     * @throws LmQuatreInvalidArgumentException
     */
    public static function withPhoneNumberEmpty(): LmQuatre
    {
        $infoDescriptionModel = InfoDescriptionModelFaker::new();

        return new LmQuatre(
            infoDescriptionModel: $infoDescriptionModel,
            owner: LmQuatreOwner::fromValue('Math'),
            adresse: LmQuatreAdresse::fromValue('33 rue du test'),
            email: LmQuatreEmail::fromValue('test@exemple.com'),
            phoneNumber: LmQuatrePhoneNumber::fromValue(''),
            companyCreateDate: new DateTimeImmutable('2000-03-31')
        );
    }
}
