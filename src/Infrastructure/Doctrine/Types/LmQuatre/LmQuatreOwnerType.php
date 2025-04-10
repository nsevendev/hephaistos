<?php

declare(strict_types=1);

namespace Heph\Infrastructure\Doctrine\Types\LmQuatre;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Heph\Entity\LmQuatre\ValueObject\LmQuatreOwner;
use Heph\Infrastructure\ApiResponse\Exception\Custom\LmQuatre\LmQuatreInvalidArgumentException;
use Heph\Infrastructure\ApiResponse\Exception\Error\Error;

final class LmQuatreOwnerType extends Type
{
    public function getName(): string
    {
        return 'app_lm_quatre_owner';
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL($column);
    }

    /**
     * @throws LmQuatreInvalidArgumentException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?LmQuatreOwner
    {
        if (null === $value) {
            return null;
        }

        if (false === is_string($value)) {
            throw new LmQuatreInvalidArgumentException(getMessage: 'LmQuatre owner doit être une chaine de caractères', errors: [Error::create(key: 'LmQuatreOwnerType', message: 'LmQuatre owner doit être une chaine de caractères')]);
        }

        return LmQuatreOwner::fromValue($value);
    }

    /**
     * @throws LmQuatreInvalidArgumentException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (null === $value) {
            return null;
        }

        if (false === $value instanceof LmQuatreOwner) {
            throw new LmQuatreInvalidArgumentException(getMessage: 'La valeur doit etre une instance de LmQuatreOwner', errors: [Error::create(key: 'LmQuatreOwnerType', message: 'La valeur doit etre une instance de LmQuatreOwner')]);
        }

        return $value->value();
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
