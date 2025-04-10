<?php

declare(strict_types=1);

namespace Heph\Infrastructure\Doctrine\Types\Schedule;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Heph\Entity\Schedule\ValueObject\ScheduleHoursOpenPm;
use Heph\Infrastructure\ApiResponse\Exception\Custom\Schedule\ScheduleInvalidArgumentException;
use Heph\Infrastructure\ApiResponse\Exception\Error\Error;

final class ScheduleHoursOpenPmType extends Type
{
    public function getName(): string
    {
        return 'app_schedule_hours_open_pm';
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL($column);
    }

    /**
     * @throws ScheduleInvalidArgumentException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?ScheduleHoursOpenPm
    {
        if (null === $value) {
            return null;
        }

        if (false === is_string($value)) {
            throw new ScheduleInvalidArgumentException(getMessage: 'Schedule hours_open_pm_type doit être une chaine de caractères', errors: [Error::create(key: 'ScheduleHoursOpenPmType', message: 'Schedule hours_open_pm_type doit être une chaine de caractères')]);
        }

        return ScheduleHoursOpenPm::fromValue($value);
    }

    /**
     * @throws ScheduleInvalidArgumentException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (null === $value) {
            return null;
        }

        if (false === $value instanceof ScheduleHoursOpenPm) {
            throw new ScheduleInvalidArgumentException(getMessage: 'La valeur doit etre une instance de ScheduleHoursOpenPm', errors: [Error::create(key: 'ScheduleHoursOpenPmType', message: 'La valeur doit etre une instance de ScheduleHoursOpenPm')]);
        }

        return $value->value();
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
