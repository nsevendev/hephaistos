<?php

declare(strict_types=1);

namespace Heph\Message\Command\Ping;

use Heph\Entity\Ping\Dto\PingDto;
use Heph\Entity\Ping\Ping;
use Heph\Entity\Ping\ValueObject\PingMessage;
use Heph\Entity\Ping\ValueObject\PingStatus;
use Heph\Infrastructure\ApiResponse\Exception\Custom\Mercure\MercureInvalidArgumentException;
use Heph\Infrastructure\ApiResponse\Exception\Custom\Ping\PingInvalidArgumentException;
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
     * @throws PingInvalidArgumentException
     */
    public function __invoke(CreatePingCommand $command): void
    {
        $ping = new Ping(
            status: PingStatus::fromValue($command->pingEntityCreateDto->status),
            message: PingMessage::fromValue($command->pingEntityCreateDto->message)
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
