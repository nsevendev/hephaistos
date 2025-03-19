<?php

declare(strict_types=1);

namespace Heph\Infrastructure\Doctrine\Types\Shared;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Heph\Entity\Shared\ValueObject\LibelleValueObject;
use Heph\Infrastructure\ApiResponse\Exception\Custom\Shared\GenericException;
use Heph\Infrastructure\ApiResponse\Exception\Error\Error;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class LibelleType extends Type
{
    public function getName(): string
    {
        return 'app_shared_libelle';
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getIntegerTypeDeclarationSQL($column);
    }

    /**
     * @throws GenericException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?LibelleValueObject
    {
        if (null === $value) {
            return null;
        }

        if (false === is_string($value)) {
            throw new GenericException(exception: new BadRequestHttpException(), getMessage: 'Libelle doit être une chaine de characters', errors: [Error::create(key: 'LibelleType', message: 'Libelle doit être une chaine de characters')]);
        }

        return LibelleValueObject::fromValue($value);
    }

    /**
     * @throws GenericException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (null === $value) {
            return null;
        }

        if (false === $value instanceof LibelleValueObject) {
            throw new GenericException(exception: new BadRequestHttpException(), getMessage: 'La valeur doit etre une instance de LibelleValueObject', errors: [Error::create(key: 'LibelleType', message: 'La valeur doit etre une instance de LibelleValueObject')]);
        }

        return $value->value();
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
