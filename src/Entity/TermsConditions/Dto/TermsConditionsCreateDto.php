<?php

declare(strict_types=1);

namespace Heph\Entity\TermsConditions\Dto;

use Heph\Entity\InfoDescriptionModel\Dto\InfoDescriptionModelCreateDto;
use Symfony\Component\Validator\Constraints as Assert;

readonly class TermsConditionsCreateDto
{
    public function __construct(
        #[Assert\Valid]
        public InfoDescriptionModelCreateDto $infoDescriptionModel,
    ) {}

    public static function new(string $libelle, string $description): self
    {
        return new self(
            infoDescriptionModel: InfoDescriptionModelCreateDto::new(
                $libelle,
                $description
            ),
        );
    }
}
