<?php

declare(strict_types=1);

namespace Heph\Message\Command\Ping;

use Heph\Entity\Ping\PingEntity;
use Heph\Repository\Ping\PingEntityRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'command.bus')]
readonly class CreatePingEntityHandler
{
    public function __construct(
        private PingEntityRepository $pingEntityRepository,
    ) {}

    public function __invoke(CreatePingEntityCommand $command): void
    {
        $ping = new PingEntity(
            status: $command->pingEntityCreateDto->status()->value(),
            message: $command->pingEntityCreateDto->message()->value()
        );

        $this->pingEntityRepository->save(
            ping: $ping
        );
    }
}
