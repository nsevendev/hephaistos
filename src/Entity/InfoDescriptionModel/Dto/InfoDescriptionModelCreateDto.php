<?php

declare(strict_types=1);

namespace Heph\Entity\InfoDescriptionModel\Dto;

use Heph\Entity\Shared\ValueObject\LibelleValueObject;
use Heph\Entity\Shared\ValueObject\DescriptionValueObject;
use Symfony\Component\Validator\Constraints as Assert;

readonly class InfoDescriptionModelCreateDto
{
    public function __construct(
        #[Assert\Valid]
        private LibelleValueObject $libelle,
        #[Assert\Valid]
        private DescriptionValueObject $description,
        private \DateTimeImmutable $createdAt,
        private \DateTimeImmutable $updatedAt,
    ) {}

    public static function new(string $libelle, string $description): self
    {
        return new self(
            libelle: LibelleValueObject::fromValue($libelle),
            description: DescriptionValueObject::fromValue($description),
            createdAt: new \DateTimeImmutable(),
            updatedAt: new \DateTimeImmutable(),
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

    public function createdAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }
}