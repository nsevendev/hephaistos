<?php

declare(strict_types=1);

namespace Heph\Entity\Shared\ValueObject;

use Heph\Infrastructure\Shared\Type\ValueObjectInterface;

class TestValueObject implements ValueObjectInterface
{
    private mixed $value;

    public static function fromValue(mixed $value): self
    {
        $instance = new self();
        $instance->value = $value;
        return $instance;
    }

    public function value(): mixed
    {
        return $this->value;
    }
}
