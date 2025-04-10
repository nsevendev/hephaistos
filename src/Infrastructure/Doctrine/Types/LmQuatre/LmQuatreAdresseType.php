<?php

declare(strict_types=1);

namespace Heph\Infrastructure\Doctrine\Types\LmQuatre;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Heph\Entity\LmQuatre\ValueObject\LmQuatreAdresse;
use Heph\Infrastructure\ApiResponse\Exception\Custom\LmQuatre\LmQuatreInvalidArgumentException;
use Heph\Infrastructure\ApiResponse\Exception\Error\Error;

final class LmQuatreAdresseType extends Type
{
    public function getName(): string
    {
        return 'app_lm_quatre_adresse';
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL($column);
    }

    /**
     * @throws LmQuatreInvalidArgumentException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?LmQuatreAdresse
    {
        if (null === $value) {
            return null;
        }

        if (false === is_string($value)) {
            throw new LmQuatreInvalidArgumentException(getMessage: 'LmQuatre adresse doit être une chaine de caractères', errors: [Error::create(key: 'LmQuatreAdresseType', message: 'LmQuatre adresse doit être une chaine de caractères')]);
        }

        return LmQuatreAdresse::fromValue($value);
    }

    /**
     * @throws LmQuatreInvalidArgumentException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (null === $value) {
            return null;
        }

        if (false === $value instanceof LmQuatreAdresse) {
            throw new LmQuatreInvalidArgumentException(getMessage: 'La valeur doit etre une instance de LmQuatreAdresse', errors: [Error::create(key: 'LmQuatreAdresseType', message: 'La valeur doit etre une instance de LmQuatreAdresse')]);
        }

        return $value->value();
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
