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
     * @param EngineRemap[] $data
     *
     * @return EngineRemapDto[]
     */
    public static function toListEngineRemap(array $data): array
    {
        $listEngineRemap = [];

        foreach ($data as $engineRemap) {
            $listEngineRemap[] = self::fromArray($engineRemap);
        }

        return $listEngineRemap;
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
