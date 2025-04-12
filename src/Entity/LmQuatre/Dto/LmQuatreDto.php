<?php

declare(strict_types=1);

namespace Heph\Entity\LmQuatre\Dto;

use Heph\Entity\InfoDescriptionModel\Dto\InfoDescriptionModelDto;
use Heph\Entity\LmQuatre\LmQuatre;

class LmQuatreDto
{
    public function __construct(
        public string $id,
        public InfoDescriptionModelDto $infoDescriptionModel,
        public string $owner,
        public string $adresse,
        public string $email,
        public string $phoneNumber,
        public string $companyCreateDate,
        public string $createdAt,
        public string $updatedAt,
    ) {}

    public static function fromArray(LmQuatre $data): self
    {
        return new self(
            id: (string) $data->id(),
            infoDescriptionModel: InfoDescriptionModelDto::fromEntity($data->infoDescriptionModel()),
            owner: $data->owner()->value(),
            adresse: $data->adresse()->value(),
            email: $data->email()->value(),
            phoneNumber: $data->phoneNumber()->value(),
            companyCreateDate: $data->companyCreateDate()->format('Y-m-d H:i:s'),
            createdAt: $data->createdAt()->format('Y-m-d H:i:s'),
            updatedAt: $data->updatedAt()->format('Y-m-d H:i:s'),
        );
    }

    /**
     * @param LmQuatre[] $data
     *
     * @return LmQuatreDto[]
     */
    public static function toListLmQuatre(array $data): array
    {
        $listLmQuatre = [];

        foreach ($data as $lmQuatre) {
            $listLmQuatre[] = self::fromArray($lmQuatre);
        }

        return $listLmQuatre;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'infoDescriptionModel' => $this->infoDescriptionModel,
            'owner' => $this->owner,
            'adresse' => $this->adresse,
            'email' => $this->email,
            'phoneNumber' => $this->phoneNumber,
            'companyCreateDate' => $this->companyCreateDate,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
        ];
    }
}
