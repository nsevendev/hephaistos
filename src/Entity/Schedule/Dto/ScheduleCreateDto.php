<?php

declare(strict_types=1);

namespace Heph\Entity\Schedule\Dto;

use Heph\Entity\Shared\ValueObject\DayValueObject;
use Heph\Entity\Shared\ValueObject\HoursOpenAmValueObject;
use Heph\Entity\Shared\ValueObject\HoursCloseAmValueObject;
use Heph\Entity\Shared\ValueObject\HoursOpenPmValueObject;
use Heph\Entity\Shared\ValueObject\HoursClosePmValueObject;
use Symfony\Component\Validator\Constraints as Assert;

readonly class ScheduleCreateDto
{
    public function __construct(
        #[Assert\Valid]
        private DayValueObject $day,
        #[Assert\Valid]
        private HoursOpenAmValueObject $hoursOpenAm,
        #[Assert\Valid]
        private HoursCloseAmValueObject $hoursCloseAm,
        #[Assert\Valid]
        private HoursOpenPmValueObject $hoursOpenPm,
        #[Assert\Valid]
        private HoursClosePmValueObject $hoursClosePm,
    ) {}

    public static function new(
        string $day,
        string $hoursOpenAm,
        string $hoursCloseAm,
        string $hoursOpenPm,
        string $hoursClosePm
    ): self {
        return new self(
            day: DayValueObject::fromValue($day),
            hoursOpenAm: HoursOpenAmValueObject::fromValue($hoursOpenAm),
            hoursCloseAm: HoursCloseAmValueObject::fromValue($hoursCloseAm),
            hoursOpenPm: HoursOpenPmValueObject::fromValue($hoursOpenPm),
            hoursClosePm: HoursClosePmValueObject::fromValue($hoursClosePm),
        );
    }

    public function day(): DayValueObject
    {
        return $this->day;
    }

    public function hoursOpenAm(): HoursOpenAmValueObject
    {
        return $this->hoursOpenAm;
    }

    public function hoursCloseAm(): HoursCloseAmValueObject
    {
        return $this->hoursCloseAm;
    }

    public function hoursOpenPm(): HoursOpenPmValueObject
    {
        return $this->hoursOpenPm;
    }

    public function hoursClosePm(): HoursClosePmValueObject
    {
        return $this->hoursClosePm;
    }
}