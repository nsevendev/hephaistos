<?php

declare(strict_types=1);

namespace Heph\Entity\EngineRemap\Dto;

use Heph\Entity\InfoDescriptionModel\Dto\InfoDescriptionModelCreateDto;
use Symfony\Component\Validator\Constraints as Assert;

readonly class EngineRemapCreateDto
{
    public function __construct(
        #[Assert\NotBlank]
        private InfoDescriptionModelCreateDto $infoDescriptionModel,

    ) {}

    public function infoDescriptionModel(): InfoDescriptionModelCreateDto
    {
        return $this->infoDescriptionModel;
    }
}
