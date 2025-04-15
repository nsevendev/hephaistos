<?php

declare(strict_types=1);

namespace Heph\Infrastructure\Doctrine\Types\Users;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Heph\Entity\Users\ValueObject\UsersUsername;
use Heph\Infrastructure\ApiResponse\Exception\Custom\Users\UsersInvalidArgumentException;
use Heph\Infrastructure\ApiResponse\Exception\Error\Error;

final class UsersUsernameType extends Type
{
    public function getName(): string
    {
        return 'app_users_username';
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL($column);
    }

    /**
     * @throws UsersInvalidArgumentException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?UsersUsername
    {
        if (null === $value) {
            return null;
        }

        if (false === is_string($value)) {
            throw new UsersInvalidArgumentException(getMessage: 'Users username doit être une chaine de caractères', errors: [Error::create(key: 'UsersUsernameType', message: 'Users username doit être une chaine de caractères')]);
        }

        return UsersUsername::fromValue($value);
    }

    /**
     * @throws UsersInvalidArgumentException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (null === $value) {
            return null;
        }

        if (is_string($value)) {
            return $value;
        }

        if (false === $value instanceof UsersUsername) {
            throw new UsersInvalidArgumentException(getMessage: 'La valeur doit etre une instance de UsersUsername', errors: [Error::create(key: 'UsersUsernameType', message: 'La valeur doit etre une instance de UsersUsername')]);
        }

        return $value->value();
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
