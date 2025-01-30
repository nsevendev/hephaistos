<?php

declare(strict_types=1);

namespace Heph\Entity\InfoDescriptionModel\Dto;

use Heph\Entity\Shared\ValueObject\DescriptionValueObject;
use Heph\Entity\Shared\ValueObject\LibelleValueObject;
use Symfony\Component\Validator\Constraints as Assert;

readonly class InfoDescriptionModelCreateDto
{
    public function __construct(
        #[Assert\Uuid]
        private string $id,
        #[Assert\Valid]
        private LibelleValueObject $libelle,
        #[Assert\Valid]
        private DescriptionValueObject $description,
        #[Assert\NotBlank]
        private \DateTimeImmutable $createdAt,
        #[Assert\NotBlank]
        private \DateTimeImmutable $updatedAt,
    ) {}

    public static function new(
        string $id,
        string $libelle,
        string $description,
        \DateTimeImmutable $createdAt,
        \DateTimeImmutable $updatedAt,
    ): self {
        return new self(
            id: $id,
            libelle: LibelleValueObject::fromValue($libelle),
            description: DescriptionValueObject::fromValue($description),
            createdAt: $createdAt,
            updatedAt: $updatedAt,
        );
    }

    public function id(): string
    {
        return $this->id;
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
