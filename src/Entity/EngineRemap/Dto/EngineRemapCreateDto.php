<?php

declare(strict_types=1);

namespace Heph\Entity\EngineRemap\Dto;

use Heph\Entity\InfoDescriptionModel\Dto\InfoDescriptionModelCreateDto;
use Symfony\Component\Validator\Constraints as Assert;

readonly class EngineRemapCreateDto
{
    public function __construct(
        #[Assert\Valid]
        private InfoDescriptionModelCreateDto $infoDescriptionModel,

    ) {}

    public static function new(string $libelle, string $description): self
    {
        return new self(
            infoDescriptionModel: InfoDescriptionModelCreateDto::new(
                $libelle,
                $description
            )
        );
    }

    public function infoDescriptionModel(): InfoDescriptionModelCreateDto
    {
        return $this->infoDescriptionModel;
    }
}
