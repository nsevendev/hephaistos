<?php

declare(strict_types=1);

namespace Heph\Message\Command\Ping;

use Heph\Repository\Ping\PingRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'command.bus')]
class DeletePingHandler
{
    public function __construct(private PingRepository $pingRepository) {}

    public function __invoke(DeletePingCommand $command): void
    {
        $this->pingRepository->remove($command->id);
    }
}
