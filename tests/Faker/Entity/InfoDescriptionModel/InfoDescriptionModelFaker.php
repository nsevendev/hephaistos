<?php

declare(strict_types=1);

namespace Heph\Tests\Faker\Entity\InfoDescriptionModel;

use Heph\Entity\InfoDescriptionModel\InfoDescriptionModel;
use Heph\Entity\Shared\ValueObject\DescriptionValueObject;
use Heph\Entity\Shared\ValueObject\LibelleValueObject;
use Heph\Infrastructure\ApiResponse\Exception\Custom\Shared\GenericException;

final class InfoDescriptionModelFaker
{
    /**
     * @throws GenericException
     */
    public static function new(): InfoDescriptionModel
    {
        return new InfoDescriptionModel(
            libelle: LibelleValueObject::fromValue('libelle test'),
            description: DescriptionValueObject::fromValue('description test')
        );
    }

    /**
     * @throws GenericException
     */
    public static function withLibelleMoreLonger(): InfoDescriptionModel
    {
        return new InfoDescriptionModel(
            libelle: LibelleValueObject::fromValue('avec un libelle trop long avec un libelle trop long avec un libelle trop long avec un libelle trop long avec un libelle trop long avec un libelle trop long avec un libelle trop long avec un libelle trop long avec un libelle trop long avec un libelle trop long avec un libelle trop long avec un libelle trop long avec un libelle trop long avec un libelle trop longavec un libelle trop long avec un libelle trop long avec un libelle trop long avec un libelle trop long avec un libelle trop long avec un libelle trop long avec un libelle trop long avec un libelle trop long avec un libelle trop long avec un libelle trop long avec un libelle trop long avec un libelle trop long avec un libelle trop long avec un libelle trop long avec un libelle trop long avec un libelle trop long avec un libelle trop long avec un libelle trop long avec un libelle trop long avec un libelle trop long avec un libelle trop long avec un libelle trop long avec un libelle trop long avec un libelle trop long avec un libelle trop long avec un libelle trop long avec un libelle trop long avec un libelle trop longavec un libelle trop long avec un libelle trop long avec un libelle trop long avec un libelle trop long avec un libelle trop long avec un libelle trop long avec un libelle trop long avec un libelle trop long avec un libelle trop long avec un libelle trop long avec un libelle trop long avec un libelle trop long avec un libelle trop long avec un libelle trop long avec un libelle trop long avec un libelle trop long avec un libelle trop long avec un libelle trop long avec un libelle trop long avec un libelle trop long avec un libelle trop long avec un libelle trop long avec un libelle trop long avec un libelle trop long avec un libelle trop long avec un libelle trop long avec un libelle trop long avec un libelle trop longavec un libelle trop long avec un libelle trop long avec un libelle trop long avec un libelle trop long avec un libelle trop long avec un libelle trop long avec un libelle trop long avec un libelle trop long avec un libelle trop long avec un libelle trop long avec un libelle trop long avec un libelle trop long avec un libelle trop long avec un libelle trop long'),
            description: DescriptionValueObject::fromValue('description test')
        );
    }

    /**
     * @throws GenericException
     */
    public static function withLibelleEmpty(): InfoDescriptionModel
    {
        return new InfoDescriptionModel(
            libelle: LibelleValueObject::fromValue(''),
            description: DescriptionValueObject::fromValue('description test')
        );
    }

    /**
     * @throws GenericException
     */
    public static function withDescriptionMoreLonger(): InfoDescriptionModel
    {
        return new InfoDescriptionModel(
            libelle: LibelleValueObject::fromValue('libelle test'),
            description: DescriptionValueObject::fromValue('avec une description trop long avec une description trop long avec une description trop long avec une description trop long avec une description trop long avec une description trop long avec une description trop long avec une description trop long avec une description trop long avec une description trop long avec une description trop long avec une description trop long avec une description trop long avec une description trop longavec une description trop long avec une description trop long avec une description trop long avec une description trop long avec une description trop long avec une description trop long avec une description trop long avec une description trop long avec une description trop long avec une description trop long avec une description trop long avec une description trop long avec une description trop long avec une description trop long avec une description trop long avec une description trop long avec une description trop long avec une description trop long avec une description trop long avec une description trop long avec une description trop long avec une description trop long avec une description trop long avec une description trop long avec une description trop long avec une description trop long avec une description trop long avec une description trop longavec une description trop long avec une description trop long avec une description trop long avec une description trop long avec une description trop long avec une description trop long avec une description trop long avec une description trop long avec une description trop long avec une description trop long avec une description trop long avec une description trop long avec une description trop long avec une description trop long')
        );
    }

    /**
     * @throws GenericException
     */
    public static function withDescriptionEmpty(): InfoDescriptionModel
    {
        return new InfoDescriptionModel(
            libelle: LibelleValueObject::fromValue('libelle test'),
            description: DescriptionValueObject::fromValue('')
        );
    }
}
