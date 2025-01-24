<?php

declare(strict_types=1);

namespace Heph\Message\Command\Ping;

use Heph\Entity\Ping\Dto\PingEntityCreateDto;

class CreatePingEntityCommand
{
    public function __construct(
        public PingEntityCreateDto $pingEntityCreateDto,
    ) {}
}
