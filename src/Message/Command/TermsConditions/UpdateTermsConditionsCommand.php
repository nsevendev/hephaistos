<?php

declare(strict_types=1);

namespace Heph\Message\Command\TermsConditions;

use Heph\Entity\TermsConditions\Dto\TermsConditionsUpdateDto;

class UpdateTermsConditionsCommand
{
    public function __construct(
        public readonly TermsConditionsUpdateDto $termsConditionsUpdateDto,
        public readonly string $id,
    ) {}
}
