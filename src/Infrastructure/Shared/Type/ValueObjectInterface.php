<?php

declare(strict_types=1);

namespace Heph\Infrastructure\Shared\Type;

interface ValueObjectInterface
{
    public static function fromValue(mixed $value): self;

    public function value(): mixed;
}
