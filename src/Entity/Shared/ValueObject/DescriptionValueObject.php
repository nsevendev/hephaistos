<?php

declare(strict_types=1);

namespace Heph\Entity\Shared\ValueObject;

use Heph\Infrastructure\Shared\Type\ValueObjectInterface;
use Stringable;
use Symfony\Component\Validator\Constraints as Assert;

readonly class DescriptionValueObject implements Stringable, ValueObjectInterface
{
    public function __construct(
        #[Assert\NotBlank(message: 'La description est requis.')]
        private string $value,
    ) {}

    public static function fromValue(string|int|float|bool $value): self
    {
        return new self(value: (string) $value);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
