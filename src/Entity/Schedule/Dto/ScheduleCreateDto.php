<?php

declare(strict_types=1);

namespace Heph\Entity\Schedule\Dto;

use Heph\Entity\Shared\ValueObject\DayValueObject;
use Heph\Entity\Shared\ValueObject\HoursEndValueObject;
use Heph\Entity\Shared\ValueObject\HoursStartValueObject;
use Symfony\Component\Validator\Constraints as Assert;

readonly class ScheduleCreateDto
{
    public function __construct(
        #[Assert\Valid]
        private DayValueObject $day,
        #[Assert\Valid]
        private HoursStartValueObject $hoursStart,
        #[Assert\Valid]
        private HoursEndValueObject $hoursEnd,
    ) {}

    public static function new(string $day, string $hoursStart, string $hoursEnd): self
    {
        return new self(
            day: DayValueObject::fromValue($day),
            hoursStart: HoursStartValueObject::fromValue($hoursStart),
            hoursEnd: HoursEndValueObject::fromValue($hoursEnd),
        );
    }

    public function day(): DayValueObject
    {
        return $this->day;
    }

    public function hours_start(): HoursStartValueObject
    {
        return $this->hoursStart;
    }

    public function hours_end(): HoursEndValueObject
    {
        return $this->hoursEnd;
    }
}
