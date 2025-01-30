<?php

declare(strict_types=1);

namespace Heph\Message\Command\Ping;

use Heph\Entity\Ping\Dto\PingDto;
use Heph\Entity\Ping\Ping;
use Heph\Infrastructure\ApiResponse\Exception\Custom\Mercure\MercureInvalidArgumentException;
use Heph\Infrastructure\Mercure\MercurePublish;
use Heph\Repository\Ping\PingRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'command.bus')]
readonly class CreatePingHandler
{
    public function __construct(
        private PingRepository $pingEntityRepository,
        private MercurePublish $mercurePublish,
    ) {}

    /**
     * @throws MercureInvalidArgumentException
     */
    public function __invoke(CreatePingCommand $command): void
    {
        $ping = new Ping(
            status: $command->pingEntityCreateDto->status()->value(),
            message: $command->pingEntityCreateDto->message()->value()
        );

        $this->pingEntityRepository->save(
            ping: $ping
        );

        $pingDto = PingDto::fromArray($ping);

        $this->mercurePublish->publish(
            topic: '/ping-created',
            data: $pingDto->toArray()
        );
    }
}
