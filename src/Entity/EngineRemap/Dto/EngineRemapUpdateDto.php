<?php

declare(strict_types=1);

namespace Heph\Entity\EngineRemap\Dto;

class EngineRemapUpdateDto
{
    public function __construct(
        private readonly ?string $libelle,
        private readonly ?string $description,
    ) {}

    public function libelle(): ?string
    {
        return $this->libelle;
    }

    public function description(): ?string
    {
        return $this->description;
    }

    /**
     * @param array<string, string|null> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            libelle: $data['libelle'] ?? null,
            description: $data['description'] ?? null
        );
    }
}
