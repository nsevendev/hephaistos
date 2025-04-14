<?php

declare(strict_types=1);

namespace Heph\Message\Query\CarSales;

use Heph\Entity\CarSales\CarSales;
use Heph\Entity\CarSales\Dto\CarSalesDto;
use Heph\Repository\CarSales\CarSalesRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetFirstCarSalesHandler
{
    public function __construct(private CarSalesRepository $carSalesRepository) {}

    public function __invoke(GetFirstCarSalesQuery $query): ?CarSalesDto
    {
        /** @var CarSales|null $firstCarSales */
        $firstCarSales = $this->carSalesRepository->findOneBy([]);

        return $firstCarSales ? CarSalesDto::fromArray($firstCarSales) : null;
    }
}
