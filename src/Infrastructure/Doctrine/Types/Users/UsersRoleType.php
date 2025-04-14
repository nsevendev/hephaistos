<?php

declare(strict_types=1);

namespace Heph\Infrastructure\Doctrine\Types\Users;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Heph\Entity\Users\ValueObject\UsersRole;
use Heph\Infrastructure\ApiResponse\Exception\Custom\Users\UsersInvalidArgumentException;
use Heph\Infrastructure\ApiResponse\Exception\Error\Error;

final class UsersRoleType extends Type
{
    public function getName(): string
    {
        return 'app_users_role';
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL($column);
    }

    /**
     * @throws UsersInvalidArgumentException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?UsersRole
    {
        if (null === $value) {
            return null;
        }

        if (false === is_string($value)) {
            throw new UsersInvalidArgumentException(getMessage: 'Users role doit être une chaine de caractères', errors: [Error::create(key: 'UsersRoleType', message: 'Users role doit être une chaine de caractères')]);
        }

        return UsersRole::fromValue($value);
    }

    /**
     * @throws UsersInvalidArgumentException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (null === $value) {
            return null;
        }

        if (false === $value instanceof UsersRole) {
            throw new UsersInvalidArgumentException(getMessage: 'La valeur doit etre une instance de UsersRole', errors: [Error::create(key: 'UsersRoleType', message: 'La valeur doit etre une instance de UsersRole')]);
        }

        return $value->value();
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
