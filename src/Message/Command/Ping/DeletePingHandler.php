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
     * @return void|null
     *
     * @throws MercureInvalidArgumentException
     */
    public function __invoke(DeletePingCommand $command)
    {
        $ping = $this->pingRepository->find($command->id);

        if (null === $ping) {
            return;
        }

        $pingDto = PingPublishDeletedDto::fromArray($ping);

        $this->pingRepository->remove($ping);

        $this->mercurePublish->publish(
            topic: '/ping-deleted',
            data: $pingDto->toArray()
        );
    }
}
