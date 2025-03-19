<?php

declare(strict_types=1);

namespace Heph\Entity\Shared\ValueObject;

use JsonSerializable;
use Stringable;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class LibelleValueObject implements Stringable, JsonSerializable
{
    public function __construct(
        #[Assert\NotBlank(message: 'Le libelle est requis.')]
        #[Assert\Length(max: 75, maxMessage: 'Le libelle doit contenir au plus {{ limit }} caractères.')]
        private string $value,
    ) {}

    public static function fromValue(string $value): self
    {
        return new self(value: $value);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }

    public function jsonSerialize(): string
    {
        return $this->value;
    }
}
