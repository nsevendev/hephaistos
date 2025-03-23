<?php

declare(strict_types=1);

namespace Heph\Infrastructure\Doctrine\Types\Shared;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Heph\Entity\Shared\ValueObject\DescriptionValueObject;
use Heph\Infrastructure\ApiResponse\Exception\Custom\Shared\GenericException;
use Heph\Infrastructure\ApiResponse\Exception\Error\Error;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class DescriptionType extends Type
{
    public function getName(): string
    {
        return 'app_shared_description';
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL($column);
    }

    /**
     * @throws GenericException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?DescriptionValueObject
    {
        if (null === $value) {
            return null;
        }

        if (false === is_string($value)) {
            throw new GenericException(exception: new BadRequestHttpException(), getMessage: 'Description doit être une chaine de characters', errors: [Error::create(key: 'DescriptionType', message: 'Description doit être une chaine de characters')]);
        }

        return DescriptionValueObject::fromValue($value);
    }

    /**
     * @throws GenericException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (null === $value) {
            return null;
        }

        if (false === $value instanceof DescriptionValueObject) {
            throw new GenericException(exception: new BadRequestHttpException(), getMessage: 'La valeur doit etre une instance de DescriptionValueObject', errors: [Error::create(key: 'DescriptionType', message: 'La valeur doit etre une instance de DescriptionValueObject')]);
        }

        return $value->value();
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
