<?php

declare(strict_types=1);

namespace Heph\Entity\infoDescriptionModel\Dto;

use Heph\Entity\InfoDescriptionModel\InfoDescriptionModel;

class InfoDescriptionModelDto
{
    public function __construct(
        public string $id,
        public string $libelle,
        public string $description,
        public string $createdAt,
        public string $updatedAt,
    ) {}

    public static function fromArray(InfoDescriptionModel $data): self
    {
        return new self(
            id: (string) $data->id(),
            libelle: $data->libelle()->value(),
            description: $data->description()->value(),
            createdAt: $data->createdAt()->format('Y-m-d H:i:s'),
            updatedAt: $data->updatedAt()->format('Y-m-d H:i:s'),
        );
    }

    /**
     * @param InfoDescriptionModel[] $data
     *
     * @return InfoDescriptionModelDto[]
     */
    public static function toListInfoDescriptionModel(array $data): array
    {
        $listInfoDescriptionModel = [];

        foreach ($data as $infoDescriptionModel) {
            $listInfoDescriptionModel[] = self::fromArray($infoDescriptionModel);
        }

        return $listInfoDescriptionModel;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'libelle' => $this->libelle,
            'description' => $this->description,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
        ];
    }
}
