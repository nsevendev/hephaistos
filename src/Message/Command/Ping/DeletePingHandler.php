<?php

declare(strict_types=1);

namespace Heph\Message\Command\Ping;

use Heph\Entity\Ping\Dto\PingPublishDeletedDto;
use Heph\Infrastructure\ApiResponse\Exception\Custom\Mercure\MercureInvalidArgumentException;
use Heph\Infrastructure\Mercure\MercurePublish;
use Heph\Repository\Ping\PingRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'command.bus')]
class DeletePingHandler
{
    public function __construct(private readonly PingRepository $pingRepository, private readonly MercurePublish $mercurePublish) {}

    /**
     * @throws MercureInvalidArgumentException
     */
    public function __invoke(DeletePingCommand $command): void
    {
        $ping = $this->pingRepository->find($command->id);

        $pingDto = PingPublishDeletedDto::fromArray($ping);

        if(null === $ping) {
            return;
        }

        $this->pingRepository->remove($ping);

        $this->mercurePublish->publish(
            topic: '/ping-deleted',
            data: $pingDto->toArray()
        );
    }
}
