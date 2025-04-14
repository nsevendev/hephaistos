<?php

declare(strict_types=1);

namespace Heph\Entity\Users\ValueObject;

use Heph\Infrastructure\ApiResponse\Exception\Custom\Users\UsersInvalidArgumentException;
use Heph\Infrastructure\ApiResponse\Exception\Error\Error;
use JsonSerializable;
use Stringable;

readonly class UsersUsername implements Stringable, JsonSerializable
{
    public function __construct(private string $value) {}

    /**
     * @throws UsersInvalidArgumentException
     */
    public static function fromValue(string $value): self
    {
        $valueFormated = trim($value);

        if ('' === $valueFormated) {
            throw new UsersInvalidArgumentException(getMessage: 'Users username ne peux pas etre vide', errors: [Error::create(key: 'UsersUsername', message: 'Users username ne peux pas etre vide')]);
        }

        if (mb_strlen($valueFormated) > 255) {
            throw new UsersInvalidArgumentException(getMessage: 'Users username ne peux pas etre supérieur à 255 caractères', errors: [Error::create(key: 'UsersUsername', message: 'Users username ne peux pas etre supérieur à 255 caractères')]);
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
