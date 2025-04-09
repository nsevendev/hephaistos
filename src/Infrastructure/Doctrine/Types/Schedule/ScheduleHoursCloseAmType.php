<?php

declare(strict_types=1);

namespace Heph\Infrastructure\Doctrine\Types\Schedule;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Heph\Entity\Schedule\ValueObject\ScheduleHoursCloseAm;
use Heph\Infrastructure\ApiResponse\Exception\Custom\Schedule\ScheduleInvalidArgumentException;
use Heph\Infrastructure\ApiResponse\Exception\Error\Error;

final class ScheduleHoursCloseAmType extends Type
{
    public function getName(): string
    {
        return 'app_schedule_hours_close_am_type';
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL($column);
    }

    /**
     * @throws ScheduleInvalidArgumentException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?ScheduleHoursCloseAm
    {
        if (null === $value) {
            return null;
        }

        if (false === is_string($value)) {
            throw new ScheduleInvalidArgumentException(getMessage: 'Schedule hours_close_am_type doit être une chaine de caractères', errors: [Error::create(key: 'ScheduleHoursCloseAmType', message: 'Schedule hours_close_am_type doit être une chaine de caractères')]);
        }

        return ScheduleHoursCloseAm::fromValue($value);
    }

    /**
     * @throws ScheduleInvalidArgumentException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (null === $value) {
            return null;
        }

        if (false === $value instanceof ScheduleHoursCloseAm) {
            throw new ScheduleInvalidArgumentException(getMessage: 'La valeur doit etre une instance de ScheduleHoursCloseAm', errors: [Error::create(key: 'ScheduleHoursCloseAmType', message: 'La valeur doit etre une instance de ScheduleHoursCloseAm')]);
        }

        return $value->value();
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
