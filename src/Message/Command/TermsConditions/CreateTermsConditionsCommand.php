<?php

declare(strict_types=1);

namespace Heph\Message\Command\TermsConditions;

use Heph\Entity\TermsConditions\Dto\TermsConditionsCreateDto;

class CreateTermsConditionsCommand
{
    public function __construct(
        public TermsConditionsCreateDto $termsConditionsCreateDto,
    ) {}
}
