<?php

declare(strict_types=1);

namespace Heph\Entity\EngineRemap\Dto;

use Heph\Entity\EngineRemap\EngineRemap;

class EngineRemapDto
{
    public function __construct(
        public string $id,
        public string $infoDescriptionModelId,
        public string $createdAt,
        public string $updatedAt,
    ) {}

    public static function fromArray(EngineRemap $data): self
    {
        return new self(
            id: (string) $data->id(),
            infoDescriptionModelId: (string) $data->infoDescriptionModel()->id(),
            createdAt: $data->createdAt()->format('Y-m-d H:i:s'),
            updatedAt: $data->updatedAt()->format('Y-m-d H:i:s'),
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'infoDescriptionModelId' => $this->infoDescriptionModelId,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
        ];
    }
}
