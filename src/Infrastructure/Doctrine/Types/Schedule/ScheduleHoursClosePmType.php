<?php

declare(strict_types=1);

namespace Heph\Infrastructure\Doctrine\Types\Schedule;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Heph\Entity\Schedule\ValueObject\ScheduleHoursClosePm;
use Heph\Infrastructure\ApiResponse\Exception\Custom\Schedule\ScheduleInvalidArgumentException;
use Heph\Infrastructure\ApiResponse\Exception\Error\Error;

final class ScheduleHoursClosePmType extends Type
{
    public function getName(): string
    {
        return 'app_schedule_hours_close_pm_type';
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL($column);
    }

    /**
     * @throws ScheduleInvalidArgumentException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?ScheduleHoursClosePm
    {
        if (null === $value) {
            return null;
        }

        if (false === is_string($value)) {
            throw new ScheduleInvalidArgumentException(getMessage: 'Schedule hours_close_pm_type doit être une chaine de caractères', errors: [Error::create(key: 'ScheduleHoursClosePmType', message: 'Schedule hours_close_pm_type doit être une chaine de caractères')]);
        }

        return ScheduleHoursClosePm::fromValue($value);
    }

    /**
     * @throws ScheduleInvalidArgumentException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (null === $value) {
            return null;
        }

        if (false === $value instanceof ScheduleHoursClosePm) {
            throw new ScheduleInvalidArgumentException(getMessage: 'La valeur doit etre une instance de ScheduleHoursClosePm', errors: [Error::create(key: 'ScheduleHoursClosePmType', message: 'La valeur doit etre une instance de ScheduleHoursClosePm')]);
        }

        return $value->value();
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
