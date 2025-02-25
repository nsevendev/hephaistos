<?php

declare(strict_types=1);

namespace Heph\Entity\EngineRemap\Dto;

use Heph\Entity\Shared\ValueObject\DescriptionValueObject;
use Heph\Entity\Shared\ValueObject\LibelleValueObject;
use Symfony\Component\Validator\Constraints as Assert;

class EngineRemapUpdateDto
{
    public function __construct(
        #[Assert\Valid]
        private LibelleValueObject $libelle,
        #[Assert\Valid]
        private DescriptionValueObject $description,
    ) {}

    public static function new(string $libelle, string $description): self
    {
        return new self(
            libelle: LibelleValueObject::fromValue($libelle),
            description: DescriptionValueObject::fromValue($description),
        );
    }

    public function libelle(): LibelleValueObject
    {
        return $this->libelle;
    }

    public function description(): DescriptionValueObject
    {
        return $this->description;
    }
}
