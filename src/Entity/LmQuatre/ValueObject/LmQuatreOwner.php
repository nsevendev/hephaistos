<?php

declare(strict_types=1);

namespace Heph\Entity\LmQuatre\ValueObject;

use Heph\Infrastructure\ApiResponse\Exception\Custom\LmQuatre\LmQuatreInvalidArgumentException;
use Heph\Infrastructure\ApiResponse\Exception\Error\Error;
use JsonSerializable;
use Stringable;

readonly class LmQuatreOwner implements Stringable, JsonSerializable
{
    public function __construct(private string $value) {}

    /**
     * @throws LmQuatreInvalidArgumentException
     */
    public static function fromValue(string $value): self
    {
        $valueFormated = trim($value);

        if ('' === $valueFormated) {
            throw new LmQuatreInvalidArgumentException(getMessage: 'LmQuatre owner ne peux pas etre vide', errors: [Error::create(key: 'LmQuatreOwner', message: 'LmQuatre owner ne peux pas etre vide')]);
        }

        if (mb_strlen($valueFormated) > 50) {
            throw new LmQuatreInvalidArgumentException(getMessage: 'LmQuatre owner ne peux pas etre supérieur à 50 caractères', errors: [Error::create(key: 'LmQuatreOwner', message: 'LmQuatre owner ne peux pas etre supérieur à 50 caractères')]);
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
