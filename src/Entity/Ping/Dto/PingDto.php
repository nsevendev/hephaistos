<?php

declare(strict_types=1);

namespace Heph\Entity\Ping\Dto;

use Heph\Entity\Ping\Ping;

class PingDto
{
    public function __construct(
        public string $id,
        public int $status,
        public string $message,
        public string $createdAt,
        public string $updatedAt,
    ) {}

    public static function fromArray(Ping $data): self
    {
        return new self(
            id: (string) $data->id(),
            status: $data->status(),
            message: $data->message(),
            createdAt: $data->createdAt()->format('Y-m-d H:i:s'),
            updatedAt: $data->updatedAt()->format('Y-m-d H:i:s'),
        );
    }

    /**
     * @param Ping[] $data
     *
     * @return PingDto[]
     */
    public static function toListPing(array $data): array
    {
        $listPing = [];

        foreach ($data as $ping) {
            $listPing[] = self::fromArray($ping);
        }

        return $listPing;
    }
}
