<?php

declare(strict_types=1);

namespace Heph\Entity\Schedule\ValueObject;

use Heph\Infrastructure\ApiResponse\Exception\Custom\Schedule\ScheduleInvalidArgumentException;
use Heph\Infrastructure\ApiResponse\Exception\Error\Error;
use JsonSerializable;
use Stringable;

final readonly class ScheduleHoursCloseAm implements Stringable, JsonSerializable
{
    public function __construct(private string $value) {}

    /**
     * @throws ScheduleInvalidArgumentException
     */
    public static function fromValue(string $value): self
    {
        $valueFormated = trim($value);

        if ('' === $valueFormated) {
            throw new ScheduleInvalidArgumentException(getMessage: 'Schedule hoursCloseAm ne peux pas etre vide', errors: [Error::create(key: 'ScheduleHoursCloseAm', message: 'Schedule hoursCloseAm ne peux pas etre vide')]);
        }

        return new self(value: $valueFormated);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function jsonSerialize(): string
    {
        return $this->value;
    }
}
