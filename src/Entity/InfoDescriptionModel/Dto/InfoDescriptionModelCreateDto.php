<?php

declare(strict_types=1);

namespace Heph\Entity\InfoDescriptionModel\Dto;

use Symfony\Component\Validator\Constraints as Assert;

readonly class InfoDescriptionModelCreateDto
{
    public function __construct(
        #[Assert\NotBlank(message: 'Le libelle est requis.')]
        #[Assert\Length(max: 75, maxMessage: 'Le message doit contenir au plus {{ limit }} caractères.')]
        public string $libelle,
        #[Assert\NotBlank(message: 'La description est requis.')]
        #[Assert\Length(max: 255, maxMessage: 'La description doit contenir au plus {{ limit }} caractères.')]
        public string $description,
    ) {}

    public static function new(string $libelle, string $description): self
    {
        return new self(
            libelle: $libelle,
            description: $description,
        );
    }

    public function libelle(): string
    {
        return $this->libelle;
    }

    public function description(): string
    {
        return $this->description;
    }
}
