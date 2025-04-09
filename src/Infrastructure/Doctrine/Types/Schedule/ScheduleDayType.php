<?php

declare(strict_types=1);

namespace Heph\Infrastructure\Doctrine\Types\Schedule;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Heph\Entity\Schedule\ValueObject\ScheduleDay;
use Heph\Infrastructure\ApiResponse\Exception\Custom\Schedule\ScheduleInvalidArgumentException;
use Heph\Infrastructure\ApiResponse\Exception\Error\Error;

final class ScheduleDayType extends Type
{
    public function getName(): string
    {
        return 'app_schedule_day';
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL($column);
    }

    /**
     * @throws ScheduleInvalidArgumentException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?ScheduleDay
    {
        if (null === $value) {
            return null;
        }

        if (false === is_string($value)) {
            throw new ScheduleInvalidArgumentException(getMessage: 'Schedule message doit être une chaine de caractères', errors: [Error::create(key: 'ScheduleDayType', message: 'Schedule message doit être une chaine de caractères')]);
        }

        return ScheduleDay::fromValue($value);
    }

    /**
     * @throws ScheduleInvalidArgumentException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (null === $value) {
            return null;
        }

        if (false === $value instanceof ScheduleDay) {
            throw new ScheduleInvalidArgumentException(getMessage: 'La valeur doit etre une instance de ScheduleDay', errors: [Error::create(key: 'ScheduleDayType', message: 'La valeur doit etre une instance de ScheduleDay')]);
        }

        return $value->value();
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
