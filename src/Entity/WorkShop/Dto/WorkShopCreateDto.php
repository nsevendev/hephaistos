<?php

declare(strict_types=1);

namespace Heph\Entity\WorkShop\Dto;

use Heph\Entity\InfoDescriptionModel\Dto\InfoDescriptionModelCreateDto;
use Symfony\Component\Validator\Constraints as Assert;

readonly class WorkShopCreateDto
{
    public function __construct(
        #[Assert\NotBlank(message: "L'infoDescriptionModel est requis.")]
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
