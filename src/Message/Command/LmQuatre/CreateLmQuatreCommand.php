<?php

declare(strict_types=1);

namespace Heph\Message\Command\LmQuatre;

use Heph\Entity\LmQuatre\Dto\LmQuatreCreateDto;

class CreateLmQuatreCommand
{
    public function __construct(
        public LmQuatreCreateDto $lmQuatreCreateDto,
    ) {}
}
