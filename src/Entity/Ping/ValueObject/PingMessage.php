<?php

declare(strict_types=1);

namespace Heph\Entity\Ping\ValueObject;
use Symfony\Component\Validator\Constraints as Assert;

use Stringable;

class PingMessage implements Stringable
{
    public function __construct(
        #[Assert\NotBlank(message: "Le message est requis.")]
        #[Assert\Length(max: 255, maxMessage: "Le message doit contenir au plus {{ limit }} caractères.")]
        private string $value
    ){}

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString()
    {
        return $this->value;
    }
}
