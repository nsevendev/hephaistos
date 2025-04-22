<?php

declare(strict_types=1);

namespace Heph\Entity\Users\ValueObject;

use Heph\Infrastructure\ApiResponse\Exception\Custom\Users\UsersInvalidArgumentException;
use Heph\Infrastructure\ApiResponse\Exception\Error\Error;
use JsonSerializable;
use Stringable;

readonly class UsersRole implements Stringable, JsonSerializable
{
    public function __construct(private string $value) {}

    private const roles = [
        'ROLE_ADMIN',
        'ROLE_EMPLOYEE',
    ];

    /**
     * @throws UsersInvalidArgumentException
     */
    public static function fromValue(string $value): self
    {
        $valueFormated = trim($value);

        if ('' === $valueFormated) {
            throw new UsersInvalidArgumentException(getMessage: 'Users role ne peux pas etre vide', errors: [Error::create(key: 'UsersRole', message: 'Users role ne peux pas etre vide')]);
        }

        if (!in_array($valueFormated, self::roles, true)) {
            throw new UsersInvalidArgumentException(getMessage: 'Role invalide', errors: [Error::create('UsersRole', "Le role '{$valueFormated}' n'est pas autorisé.")]);
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
