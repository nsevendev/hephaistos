<?php

declare(strict_types=1);

namespace Heph\Infrastructure\Doctrine\Types\LmQuatre;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Heph\Entity\LmQuatre\ValueObject\LmQuatreEmail;
use Heph\Infrastructure\ApiResponse\Exception\Custom\LmQuatre\LmQuatreInvalidArgumentException;
use Heph\Infrastructure\ApiResponse\Exception\Error\Error;

final class LmQuatreEmailType extends Type
{
    public function getName(): string
    {
        return 'app_lm_quatre_email';
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL($column);
    }

    /**
     * @throws LmQuatreInvalidArgumentException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?LmQuatreEmail
    {
        if (null === $value) {
            return null;
        }

        if (false === is_string($value)) {
            throw new LmQuatreInvalidArgumentException(getMessage: 'LmQuatre email doit être une chaine de caractères', errors: [Error::create(key: 'LmQuatreEmailType', message: 'LmQuatre email doit être une chaine de caractères')]);
        }

        return LmQuatreEmail::fromValue($value);
    }

    /**
     * @throws LmQuatreInvalidArgumentException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (null === $value) {
            return null;
        }

        if (false === $value instanceof LmQuatreEmail) {
            throw new LmQuatreInvalidArgumentException(getMessage: 'La valeur doit etre une instance de LmQuatreEmail', errors: [Error::create(key: 'LmQuatreEmailType', message: 'La valeur doit etre une instance de LmQuatreEmail')]);
        }

        return $value->value();
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
