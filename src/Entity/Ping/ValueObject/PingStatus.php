<?php

declare(strict_types=1);

namespace Heph\Entity\Ping\ValueObject;

use Stringable;
use Symfony\Component\Validator\Constraints as Assert;

class PingStatus implements Stringable
{
    public function __construct(
        #[Assert\NotBlank(message: "Le status est requis.")]
        #[Assert\Choice(choices: [200], message: "Le status doit être de 200")]
        private int $value
    ){}

    public static function fromString(string $value): self
    {
        return new self((int) $value);
    }

    public function value(): int
    {
        return $this->value;
    }

    public function __toString()
    {
        return (string) $this->value;
    }
}
