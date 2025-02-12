<?php

declare(strict_types=1);

namespace Heph\Entity\TermsConditions\Dto;

use Heph\Entity\InfoDescriptionModel\Dto\InfoDescriptionModelCreateDto;
use Symfony\Component\Validator\Constraints as Assert;

readonly class TermsConditionsCreateDto
{
    public function __construct(
        #[Assert\Valid]
        private InfoDescriptionModelCreateDto $infoDescriptionModel,
    ) {}

    public static function new(
        InfoDescriptionModelCreateDto $infoDescriptionModel,
    ): self {
        return new self(
            infoDescriptionModel: $infoDescriptionModel,
        );
    }

    public function infoDescriptionModel(): InfoDescriptionModelCreateDto
    {
        return $this->infoDescriptionModel;
    }
}
