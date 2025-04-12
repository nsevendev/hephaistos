<?php

declare(strict_types=1);

namespace Heph\Message\Query\LmQuatre;

use Heph\Entity\LmQuatre\Dto\LmQuatreDto;
use Heph\Entity\LmQuatre\LmQuatre;
use Heph\Repository\LmQuatre\LmQuatreRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetListLmQuatreHandler
{
    public function __construct(private LmQuatreRepository $lmQuatreRepository) {}

    /**
     * @return LmQuatreDto[]
     */
    public function __invoke(GetListLmQuatreQuery $query): array
    {
        /** @var LmQuatre[] $listLmQuatre */
        $listLmQuatre = $this->lmQuatreRepository->findAll();

        return LmQuatreDto::toListLmQuatre($listLmQuatre);
    }
}
