<?php

declare(strict_types=1);

namespace Heph\Message\Command\CarSales;

use Heph\Entity\CarSales\Dto\CarSalesCreateDto;

class CreateCarSalesCommand
{
    public function __construct(
        public CarSalesCreateDto $carSalesCreateDto,
    ) {}
}
