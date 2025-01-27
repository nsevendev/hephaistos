<?php

declare(strict_types=1);

namespace Heph\Entity\TermsConditions\Dto;

use Heph\Entity\InfoDescriptionModel\Dto\InfoDescriptionModelDto;
use Heph\Entity\TermsConditions\TermsConditions;
use Symfony\Component\Validator\Constraints as Assert;

readonly class TermsConditionsCreateDto
{
    public function __construct(
        #[Assert\Valid]
        private InfoDescriptionModelDto $infoDescriptionModel,
    ) {}

    public static function new(TermsConditions $data): self
    {
        return new self(
            infoDescriptionModel: InfoDescriptionModelDto::fromArray($data->infoDescriptionModel()),
        );
    }

    public function infoDescriptionModel(): InfoDescriptionModelDto
    {
        return $this->infoDescriptionModel;
    }
}
