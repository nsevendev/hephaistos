<?php

declare(strict_types=1);

namespace Heph\Message\Command\Ping;

class DeletePingCommand
{
    public function __construct(
        public string $id,
    ) {}
}
