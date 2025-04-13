<?php

declare(strict_types=1);

namespace Heph\Message\Command\WorkShop;

use Heph\Entity\WorkShop\Dto\WorkShopCreateDto;

class CreateWorkShopCommand
{
    public function __construct(
        public WorkShopCreateDto $workShopCreateDto,
    ) {}
}
