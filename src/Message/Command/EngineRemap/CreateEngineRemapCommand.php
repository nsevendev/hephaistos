<?php

declare(strict_types=1);

namespace Heph\Message\Command\EngineRemap;

use Heph\Entity\EngineRemap\Dto\EngineRemapCreateDto;
use Heph\Entity\InfoDescriptionModel\Dto\InfoDescriptionModelCreateDto;

class CreateEngineRemapCommand
{
    public function __construct(
        public EngineRemapCreateDto $engineRemapCreateDto,
        public InfoDescriptionModelCreateDto $infoDescriptionModelCreateDto,
    ) {}
}
