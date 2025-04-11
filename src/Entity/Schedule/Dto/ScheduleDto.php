<?php

declare(strict_types=1);

namespace Heph\Entity\Schedule\Dto;

use Heph\Entity\Schedule\Schedule;

class ScheduleDto
{
    public function __construct(
        public string $id,
        public string $day,
        public string $hoursOpenAm,
        public string $hoursCloseAm,
        public string $hoursOpenPm,
        public string $hoursClosePm,
        public string $createdAt,
        public string $updatedAt,
    ) {}

    public static function fromArray(Schedule $data): self
    {
        return new self(
            id: (string) $data->id(),
            day: $data->day()->value(),
            hoursOpenAm: $data->hoursOpenAm()->value(),
            hoursCloseAm: $data->hoursCloseAm()->value(),
            hoursOpenPm: $data->hoursOpenPm()->value(),
            hoursClosePm: $data->hoursClosePm()->value(),
            createdAt: $data->createdAt()->format('Y-m-d H:i:s'),
            updatedAt: $data->updatedAt()->format('Y-m-d H:i:s'),
        );
    }

    /**
     * @param Schedule[] $data
     *
     * @return ScheduleDto[]
     */
    public static function toListSchedule(array $data): array
    {
        $listSchedule = [];

        foreach ($data as $schedule) {
            $listSchedule[] = self::fromArray($schedule);
        }

        return $listSchedule;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'day' => $this->day,
            'hoursOpenAm' => $this->hoursOpenAm,
            'hoursCloseAm' => $this->hoursCloseAm,
            'hoursOpenPm' => $this->hoursOpenPm,
            'hoursClosePm' => $this->hoursClosePm,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
        ];
    }
}
