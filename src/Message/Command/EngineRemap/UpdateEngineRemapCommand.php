<?php

declare(strict_types=1);

namespace Heph\Message\Command\EngineRemap;

use Heph\Entity\EngineRemap\Dto\EngineRemapUpdateDto;

class UpdateEngineRemapCommand
{
    public function __construct(
        public readonly EngineRemapUpdateDto $engineRemapUpdateDto
    ) {}
}
