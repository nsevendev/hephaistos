<?php

declare(strict_types=1);

namespace Heph\Message\Query\LmQuatre;

use Heph\Entity\LmQuatre\Dto\LmQuatreDto;
use Heph\Entity\LmQuatre\LmQuatre;
use Heph\Repository\LmQuatre\LmQuatreRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetFirstLmQuatreHandler
{
    public function __construct(private LmQuatreRepository $engineRemapRepository) {}

    public function __invoke(GetFirstLmQuatreQuery $query): ?LmQuatreDto
    {
        /** @var LmQuatre|null $firstLmQuatre */
        $firstLmQuatre = $this->engineRemapRepository->findOneBy([]);

        return $firstLmQuatre ? LmQuatreDto::fromArray($firstLmQuatre) : null;
    }
}
