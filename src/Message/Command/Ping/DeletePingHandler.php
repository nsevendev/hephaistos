<?php

declare(strict_types=1);

namespace Heph\Message\Command\Ping;

use Heph\Entity\Ping\Dto\PingPublishDeletedDto;
use Heph\Infrastructure\ApiResponse\Exception\Custom\Mercure\MercureInvalidArgumentException;
use Heph\Infrastructure\ApiResponse\Exception\Custom\Ping\PingBadRequestException;
use Heph\Infrastructure\ApiResponse\Exception\Error\Error;
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
     * @throws PingBadRequestException
     */
    public function __invoke(DeletePingCommand $command)
    {
        $ping = $this->pingRepository->find($command->id);

        if (null === $ping) {
            throw new PingBadRequestException(errors: [Error::create('ping', "Aucun ping n'a été trouvé")]);
        }

        $pingDto = PingPublishDeletedDto::fromArray($ping);

        $this->pingRepository->remove($ping);

        $this->mercurePublish->publish(
            topic: '/ping-deleted',
            data: $pingDto->toArray()
        );
    }
}
