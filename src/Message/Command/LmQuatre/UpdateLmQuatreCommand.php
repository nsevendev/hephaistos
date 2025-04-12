<?php

declare(strict_types=1);

namespace Heph\Message\Command\LmQuatre;

use Heph\Entity\LmQuatre\Dto\LmQuatreUpdateDto;

class UpdateLmQuatreCommand
{
    public function __construct(
        public readonly LmQuatreUpdateDto $lmQuatreUpdateDto,
        public readonly string $id,
    ) {}
}
