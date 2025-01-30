<?php

declare(strict_types=1);

namespace Heph\Message\Command\Ping;

use Heph\Entity\Ping\Dto\PingCreateDto;

class CreatePingCommand
{
    public function __construct(
        public PingCreateDto $pingEntityCreateDto,
    ) {}
}
