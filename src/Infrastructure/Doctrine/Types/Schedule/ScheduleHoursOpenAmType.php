<?php

declare(strict_types=1);

namespace Heph\Infrastructure\Doctrine\Types\Schedule;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Heph\Entity\Schedule\ValueObject\ScheduleHoursOpenAm;
use Heph\Infrastructure\ApiResponse\Exception\Custom\Schedule\ScheduleInvalidArgumentException;
use Heph\Infrastructure\ApiResponse\Exception\Error\Error;

final class ScheduleHoursOpenAmType extends Type
{
    public function getName(): string
    {
        return 'app_schedule_hours_open_am';
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL($column);
    }

    /**
     * @throws ScheduleInvalidArgumentException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?ScheduleHoursOpenAm
    {
        if (null === $value) {
            return null;
        }

        if (false === is_string($value)) {
            throw new ScheduleInvalidArgumentException(getMessage: 'Schedule hours_open_am_type doit être une chaine de caractères', errors: [Error::create(key: 'ScheduleHoursOpenAmType', message: 'Schedule hours_open_am_type doit être une chaine de caractères')]);
        }

        return ScheduleHoursOpenAm::fromValue($value);
    }

    /**
     * @throws ScheduleInvalidArgumentException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (null === $value) {
            return null;
        }

        if (false === $value instanceof ScheduleHoursOpenAm) {
            throw new ScheduleInvalidArgumentException(getMessage: 'La valeur doit etre une instance de ScheduleHoursOpenAm', errors: [Error::create(key: 'ScheduleHoursOpenAmType', message: 'La valeur doit etre une instance de ScheduleHoursOpenAm')]);
        }

        return $value->value();
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
