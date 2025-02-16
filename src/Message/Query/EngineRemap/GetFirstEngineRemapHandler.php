<?php

declare(strict_types=1);

namespace Heph\Message\Query\EngineRemap;

use Heph\Entity\EngineRemap\Dto\EngineRemapDto;
use Heph\Entity\EngineRemap\EngineRemap;
use Heph\Infrastructure\ApiResponse\Exception\Custom\EngineRemap\EngineRemapNotFoundException;
use Heph\Repository\EngineRemap\EngineRemapRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetFirstEngineRemapHandler
{
    public function __construct(private EngineRemapRepository $engineRemapRepository) {}

    public function __invoke(GetFirstEngineRemapQuery $query): ?EngineRemapDto
    {
        /** @var EngineRemap|null $firstEngineRemap */
        $firstEngineRemap = $this->engineRemapRepository->findFirst();

        if (null === $firstEngineRemap) {
            throw new EngineRemapNotFoundException('Aucun EngineRemap trouvé.');
        }

        return EngineRemapDto::fromEntity($firstEngineRemap);
    }
}
