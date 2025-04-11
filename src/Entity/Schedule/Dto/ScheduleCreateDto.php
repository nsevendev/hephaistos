<?php

declare(strict_types=1);

namespace Heph\Entity\Schedule\Dto;

use Symfony\Component\Validator\Constraints as Assert;

readonly class ScheduleCreateDto
{
    public function __construct(
        #[Assert\NotBlank(message: 'Le day est requis.')]
        #[Assert\Choice(choices: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'], message: 'Le day doit être {{ choices }}')]
        public string $day,
        #[Assert\NotBlank(message: 'Le hours_open_am est requis.')]
        #[Assert\Choice(choices: ['close', '06:00', '06:15', '06:30', '06:45', '07:00', '07:15', '07:30', '07:45', '08:00', '08:15', '08:30', '08:45', '09:00', '09:15', '09:30', '09:45', '10:00', '10:15', '10:30', '10:45', '11:00', '11:15', '11:30', '11:45', '12:00', '12:15', '12:30', '12:45', '13:00'], message: 'Le hours_open_am doit être parmi {{ choices }}')]
        public string $hoursOpenAm,
        #[Assert\NotBlank(message: 'Le hours_close_am est requis.')]
        #[Assert\Choice(choices: ['close', '06:00', '06:15', '06:30', '06:45', '07:00', '07:15', '07:30', '07:45', '08:00', '08:15', '08:30', '08:45', '09:00', '09:15', '09:30', '09:45', '10:00', '10:15', '10:30', '10:45', '11:00', '11:15', '11:30', '11:45', '12:00', '12:15', '12:30', '12:45', '13:00'], message: 'Le hours_close_am doit être parmi {{ choices }}')]
        public string $hoursCloseAm,
        #[Assert\NotBlank(message: 'Le hours_open_pm est requis.')]
        #[Assert\Choice(choices: ['close', '13:00', '13:15', '13:30', '13:45', '14:00', '14:15', '14:30', '14:45', '15:00', '15:15', '15:30', '15:45', '16:00', '16:15', '16:30', '16:45', '17:00', '17:15', '17:30', '17:45', '18:00', '18:15', '18:30', '18:45', '19:00', '19:15', '19:30', '19:45', '20:00'], message: 'Le hours_open_pm doit être parmi {{ choices }}')]
        public string $hoursOpenPm,
        #[Assert\NotBlank(message: 'Le hours_close_pm est requis.')]
        #[Assert\Choice(choices: ['close', '13:00', '13:15', '13:30', '13:45', '14:00', '14:15', '14:30', '14:45', '15:00', '15:15', '15:30', '15:45', '16:00', '16:15', '16:30', '16:45', '17:00', '17:15', '17:30', '17:45', '18:00', '18:15', '18:30', '18:45', '19:00', '19:15', '19:30', '19:45', '20:00'], message: 'Le hours_close_pm doit être parmi {{ choices }}')]
        public string $hoursClosePm,
    ) {}

    public static function new(
        string $day,
        string $hoursOpenAm,
        string $hoursCloseAm,
        string $hoursOpenPm,
        string $hoursClosePm,
    ): self {
        return new self(
            day: $day,
            hoursOpenAm: $hoursOpenAm,
            hoursCloseAm: $hoursCloseAm,
            hoursOpenPm: $hoursOpenPm,
            hoursClosePm: $hoursClosePm,
        );
    }
}
