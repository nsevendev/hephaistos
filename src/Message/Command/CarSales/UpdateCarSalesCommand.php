<?php

declare(strict_types=1);

namespace Heph\Message\Command\CarSales;

use Heph\Entity\CarSales\Dto\CarSalesUpdateDto;

class UpdateCarSalesCommand
{
    public function __construct(
        public readonly CarSalesUpdateDto $carSalesUpdateDto,
        public readonly string $id,
    ) {}
}
