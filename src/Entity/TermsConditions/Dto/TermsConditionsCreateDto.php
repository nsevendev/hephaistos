<?php

declare(strict_types=1);

namespace Heph\Entity\TermsConditions\Dto;

use DateTimeImmutable;
use Heph\Entity\InfoDescriptionModel\Dto\InfoDescriptionModelCreateDto;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

readonly class TermsConditionsCreateDto
{
    public function __construct(
        #[Assert\Uuid]
        private Uuid $id,
        #[Assert\Valid]
        private InfoDescriptionModelCreateDto $infoDescriptionModel,
        #[Assert\NotBlank]
        private DateTimeImmutable $createdAt,
        #[Assert\NotBlank]
        private DateTimeImmutable $updatedAt,
    ) {}

    public static function new(
        InfoDescriptionModelCreateDto $infoDescriptionModel,
    ): self {
        return new self(
            id: Uuid::v7(),
            infoDescriptionModel: $infoDescriptionModel,
            createdAt: new DateTimeImmutable(),
            updatedAt: new DateTimeImmutable(),
        );
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function infoDescriptionModel(): InfoDescriptionModelCreateDto
    {
        return $this->infoDescriptionModel;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
