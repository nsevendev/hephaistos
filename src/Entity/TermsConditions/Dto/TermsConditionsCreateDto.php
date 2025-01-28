<?php

declare(strict_types=1);

namespace Heph\Entity\TermsConditions\Dto;

use Heph\Entity\InfoDescriptionModel\Dto\InfoDescriptionModelCreateDto;
use Symfony\Component\Validator\Constraints as Assert;

readonly class TermsConditionsCreateDto
{
    public function __construct(
        #[Assert\Uuid]
        private string $id,
        #[Assert\Valid]
        private InfoDescriptionModelCreateDto $infoDescriptionModel,
        #[Assert\NotBlank]
        private \DateTimeImmutable $createdAt,
        #[Assert\NotBlank]
        private \DateTimeImmutable $updatedAt,
    ) {}

    public static function new(
        string $id,
        InfoDescriptionModelCreateDto $infoDescriptionModel,
        \DateTimeImmutable $createdAt,
        \DateTimeImmutable $updatedAt
    ): self {
        return new self(
            id: $id,
            infoDescriptionModel: $infoDescriptionModel,
            createdAt: $createdAt,
            updatedAt: $updatedAt,
        );
    }

    public function id(): string
    {
        return $this->id;
    }

    public function infoDescriptionModel(): InfoDescriptionModelCreateDto
    {
        return $this->infoDescriptionModel;
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