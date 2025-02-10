<?php

declare(strict_types=1);

namespace Heph\Entity\EngineRemap\Dto;

use DateTimeImmutable;
use Heph\Entity\InfoDescriptionModel\InfoDescriptionModel;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Uid\Uuid;

readonly class EngineRemapCreateDto
{
    public function __construct(
        #[Assert\Uuid]
        private Uuid $id,
        #[Assert\Valid]
        private InfoDescriptionModel $infoDescriptionModel,
        #[Assert\Type(DateTimeImmutable::class)]
        private DateTimeImmutable $createdAt,
        #[Assert\Type(DateTimeImmutable::class)]
        private DateTimeImmutable $updatedAt,
    ) {}

    public static function new(
        InfoDescriptionModel $infoDescriptionModel
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

    public function infoDescriptionModel(): InfoDescriptionModel
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
