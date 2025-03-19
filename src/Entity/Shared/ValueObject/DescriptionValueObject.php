<?php

declare(strict_types=1);

namespace Heph\Entity\Shared\ValueObject;

use Heph\Infrastructure\ApiResponse\Exception\Custom\Shared\GenericException;
use Heph\Infrastructure\ApiResponse\Exception\Error\Error;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use JsonSerializable;
use Stringable;

readonly class DescriptionValueObject implements Stringable, JsonSerializable
{
    public function __construct(private string $value) {}

    /**
     * @throws GenericException
     */
    public static function fromValue(string $value): self
    {
        $valueFormated = trim($value);

        if ('' === $valueFormated) {
            throw new GenericException(exception: new BadRequestHttpException(), getMessage: 'Description ne peux pas etre vide', errors: [Error::create(key: 'description', message: 'Description ne peux pas etre vide')]);
        }

        if (mb_strlen($valueFormated) > 255) {
            throw new GenericException(exception: new BadRequestHttpException(), getMessage: 'Description ne peux pas etre supérieur à 255 caractères', errors: [Error::create(key: 'description', message: 'Description ne peux pas etre supérieur à 255 caractères')]);
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
