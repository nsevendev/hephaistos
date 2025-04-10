<?php

declare(strict_types=1);

namespace Heph\Entity\TermsConditions\Dto;

use Heph\Entity\InfoDescriptionModel\Dto\InfoDescriptionModelDto;
use Heph\Entity\TermsConditions\TermsConditions;
use Symfony\Component\Uid\Uuid;

class TermsConditionsDto
{
    public function __construct(
        public Uuid $id,
        public InfoDescriptionModelDto $infoDescriptionModel,
        public string $createdAt,
        public string $updatedAt,
    ) {}

    public static function fromEntity(TermsConditions $data): self
    {
        return new self(
            id: Uuid::v7(),
            infoDescriptionModel: InfoDescriptionModelDto::fromArray($data->infoDescriptionModel()),
            createdAt: $data->createdAt()->format('Y-m-d H:i:s'),
            updatedAt: $data->updatedAt()->format('Y-m-d H:i:s'),
        );
    }

    /**
     * @param TermsConditions[] $data
     *
     * @return TermsConditionsDto[]
     */
    public static function toListTermsConditions(array $data): array
    {
        $listTermsConditions = [];

        foreach ($data as $termsConditions) {
            $listTermsConditions[] = self::fromEntity($termsConditions);
        }

        return $listTermsConditions;
    }
}
