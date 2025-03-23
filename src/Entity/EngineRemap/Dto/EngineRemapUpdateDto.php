<?php

declare(strict_types=1);

namespace Heph\Entity\EngineRemap\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class EngineRemapUpdateDto
{
    public function __construct(
        #[Assert\NotBlank(message: 'Le libelle est requis.')]
        #[Assert\Length(max: 75, maxMessage: 'Le libelle doit contenir au plus {{ limit }} caractères.')]
        public string $libelle,
        #[Assert\NotBlank(message: 'La description est requis.')]
        #[Assert\Length(max: 255, maxMessage: 'La description doit contenir au plus {{ limit }} caractères.')]
        public string $description,
    ) {}

    public static function new(string $libelle, string $description): self
    {
        return new self(
            $libelle,
            $description,
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
