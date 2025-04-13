<?php

declare(strict_types=1);

namespace Heph\Message\Command\WorkShop;

use Heph\Entity\WorkShop\Dto\WorkShopUpdateDto;

class UpdateWorkShopCommand
{
    public function __construct(
        public readonly WorkShopUpdateDto $workShopUpdateDto,
        public readonly string $id,
    ) {}
}
