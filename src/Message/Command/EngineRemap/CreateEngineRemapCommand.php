<?php

declare(strict_types=1);

namespace Heph\Message\Command\EngineRemap;

use Heph\Entity\EngineRemap\Dto\EngineRemapCreateDto;

class CreateEngineRemapCommand
{
    public function __construct(
        public EngineRemapCreateDto $engineRemapCreateDto,
    ) {}
}
