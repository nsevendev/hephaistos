<?php

declare(strict_types=1);

namespace Heph\Entity\LmQuatre\ValueObject;

use Heph\Infrastructure\ApiResponse\Exception\Custom\LmQuatre\LmQuatreInvalidArgumentException;
use Heph\Infrastructure\ApiResponse\Exception\Error\Error;
use JsonSerializable;
use Stringable;

readonly class LmQuatreAdresse implements Stringable, JsonSerializable
{
    public function __construct(private string $value) {}

    /**
     * @throws LmQuatreInvalidArgumentException
     */
    public static function fromValue(string $value): self
    {
        $valueFormated = trim($value);

        if ('' === $valueFormated) {
            throw new LmQuatreInvalidArgumentException(getMessage: 'LmQuatre adresse ne peux pas etre vide', errors: [Error::create(key: 'LmQuatreAdresse', message: 'LmQuatre adresse ne peux pas etre vide')]);
        }

        if (mb_strlen($valueFormated) > 255) {
            throw new LmQuatreInvalidArgumentException(getMessage: 'LmQuatre adresse ne peux pas etre supérieur à 255 caractères', errors: [Error::create(key: 'LmQuatreAdresse', message: 'LmQuatre adresse ne peux pas etre supérieur à 255 caractères')]);
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
