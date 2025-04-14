<?php

declare(strict_types=1);

namespace Heph\Entity\CarSales\Dto;

use Heph\Entity\CarSales\CarSales;
use Heph\Entity\InfoDescriptionModel\Dto\InfoDescriptionModelDto;

class CarSalesDto
{
    public function __construct(
        public string $id,
        public InfoDescriptionModelDto $infoDescriptionModel,
        public string $createdAt,
        public string $updatedAt,
    ) {}

    public static function fromArray(CarSales $data): self
    {
        return new self(
            id: (string) $data->id(),
            infoDescriptionModel: InfoDescriptionModelDto::fromEntity($data->infoDescriptionModel()),
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
            'infoDescriptionModel' => $this->infoDescriptionModel,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
        ];
    }
}
