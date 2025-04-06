<?php

declare(strict_types=1);

namespace Heph\Infrastructure\Doctrine\Types\LmQuatre;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Heph\Entity\LmQuatre\ValueObject\LmQuatrePhoneNumber;
use Heph\Infrastructure\ApiResponse\Exception\Custom\LmQuatre\LmQuatreInvalidArgumentException;
use Heph\Infrastructure\ApiResponse\Exception\Error\Error;

final class LmQuatrePhoneNumberType extends Type
{
    public function getName(): string
    {
        return 'app_lm_quatre_phone_number';
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL($column);
    }

    /**
     * @throws LmQuatreInvalidArgumentException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?LmQuatrePhoneNumber
    {
        if (null === $value) {
            return null;
        }

        if (false === is_string($value)) {
            throw new LmQuatreInvalidArgumentException(getMessage: 'LmQuatre phone number doit être une chaine de caractères', errors: [Error::create(key: 'LmQuatrePhoneNumberType', message: 'LmQuatre phone number doit être une chaine de caractères')]);
        }

        return LmQuatrePhoneNumber::fromValue($value);
    }

    /**
     * @throws LmQuatreInvalidArgumentException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (null === $value) {
            return null;
        }

        if (false === $value instanceof LmQuatrePhoneNumber) {
            throw new LmQuatreInvalidArgumentException(getMessage: 'La valeur doit etre une instance de LmQuatrePhoneNumber', errors: [Error::create(key: 'LmQuatrePhoneNumberType', message: 'La valeur doit etre une instance de LmQuatrePhoneNumber')]);
        }

        return $value->value();
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
