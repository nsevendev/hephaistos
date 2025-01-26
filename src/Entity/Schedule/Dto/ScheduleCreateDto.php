<?php

declare(strict_types=1);

namespace Heph\Entity\Ping\Dto;

use Heph\Entity\Shared\ValueObject\ScheduleDay;
use Heph\Entity\Shared\ValueObject\ScheduleHoursStart;
use Heph\Entity\Shared\ValueObject\ScheduleHoursEnd;
use Symfony\Component\Validator\Constraints as Assert;

readonly class SharedCreateDto
{
    public function __construct(
        #[Assert\Valid]
        private ScheduleDay $day,
        #[Assert\Valid]
        private ScheduleHoursStart $hoursStart,
        #[Assert\Valid]
        private ScheduleHoursEnd $hoursEnd,
    ) {}

    public static function new(string $day, string $hoursStart, string $hoursEnd): self
    {
        return new self(
            day: ScheduleDay::fromValue($day),
            hoursStart: ScheduleHoursStart::fromValue($hoursStart),
            hoursEnd: ScheduleHoursEnd::fromValue($hoursEnd),
        );
    }

    public function day(): ScheduleDay
    {
        return $this->day;
    }

    public function message(): ScheduleHoursStart
    {
        return $this->hoursStart;
    }

    public function hours_end(): ScheduleHoursEnd
    {
        return $this->hoursEnd;
    }
}
