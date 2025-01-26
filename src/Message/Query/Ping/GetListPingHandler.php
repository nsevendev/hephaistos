<?php

declare(strict_types=1);

namespace Heph\Message\Query\Ping;

use Heph\Entity\Ping\Dto\PingDto;
use Heph\Entity\Ping\Ping;
use Heph\Repository\Ping\PingRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetListPingHandler
{
    public function __construct(private PingRepository $pingRepository) {}

    /**
     * @return PingDto[]
     */
    public function __invoke(GetListPingQuery $query): array
    {
        /** @var Ping[] $listPing */
        $listPing = $this->pingRepository->findAll();

        return PingDto::toListPing($listPing);
    }
}
