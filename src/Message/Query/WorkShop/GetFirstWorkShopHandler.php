<?php

declare(strict_types=1);

namespace Heph\Message\Query\WorkShop;

use Heph\Entity\WorkShop\Dto\WorkShopDto;
use Heph\Entity\WorkShop\WorkShop;
use Heph\Repository\WorkShop\WorkShopRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetFirstWorkShopHandler
{
    public function __construct(private WorkShopRepository $workShopRepository) {}

    public function __invoke(GetFirstWorkShopQuery $query): ?WorkShopDto
    {
        /** @var WorkShop|null $firstWorkShop */
        $firstWorkShop = $this->workShopRepository->findOneBy([]);

        return $firstWorkShop ? WorkShopDto::fromArray($firstWorkShop) : null;
    }
}
