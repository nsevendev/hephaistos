<?php

declare(strict_types=1);

namespace Heph\Entity\Schedule;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Heph\Entity\Schedule\ValueObject\ScheduleDay;
use Heph\Entity\Schedule\ValueObject\ScheduleHoursCloseAm;
use Heph\Entity\Schedule\ValueObject\ScheduleHoursClosePm;
use Heph\Entity\Schedule\ValueObject\ScheduleHoursOpenAm;
use Heph\Entity\Schedule\ValueObject\ScheduleHoursOpenPm;
use Heph\Repository\Schedule\ScheduleRepository;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ScheduleRepository::class)]
class Schedule
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private Uuid $id;

    #[ORM\Column(type: 'datetime_immutable', name: 'created_at', nullable: false)]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable', name: 'updated_at', nullable: false)]
    private DateTimeImmutable $updatedAt;


    public function __construct(
        #[ORM\Column(type: 'app_schedule_day', name: 'day', nullable: false)]
        private ScheduleDay $day,
        #[ORM\Column(type: 'app_schedule_hours_open_am', name: 'hours_open_am', nullable: false)]
        private ScheduleHoursOpenAm $hoursOpenAm,
        #[ORM\Column(type: 'app_schedule_hours_close_am', name: 'hours_close_am', nullable: false)]
        private ScheduleHoursCloseAm $hoursCloseAm,
        #[ORM\Column(type: 'app_schedule_hours_open_pm', name: 'hours_open_pm', nullable: false)]
        private ScheduleHoursOpenPm $hoursOpenPm,
        #[ORM\Column(type: 'app_schedule_hours_close_pm', name: 'hours_close_pm', nullable: false)]
        private ScheduleHoursClosePm $hoursClosePm,
    ) {
        $this->id = Uuid::v7();
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = $this->createdAt;
    }


    public function id(): Uuid
    {
        return $this->id;
    }

    public function day(): ScheduleDay
    {
        return $this->day;
    }

    public function hoursOpenAm(): ScheduleHoursOpenAm
    {
        return $this->hoursOpenAm;
    }

    public function hoursCloseAm(): ScheduleHoursCloseAm
    {
        return $this->hoursCloseAm;
    }

    public function hoursOpenPm(): ScheduleHoursOpenPm
    {
        return $this->hoursOpenPm;
    }

    public function hoursClosePm(): ScheduleHoursClosePm
    {
        return $this->hoursClosePm;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setDay(ScheduleDay $day): void
    {
        $this->day = $day;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function setHoursOpenAm(ScheduleHoursOpenAm $hoursOpenAm): void
    {
        $this->hoursOpenAm = $hoursOpenAm;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function setHoursCloseAm(ScheduleHoursCloseAm $hoursCloseAm): void
    {
        $this->hoursCloseAm = $hoursCloseAm;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function setHoursOpenPm(ScheduleHoursOpenPm $hoursOpenPm): void
    {
        $this->hoursOpenPm = $hoursOpenPm;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function setHoursClosePm(ScheduleHoursClosePm $hoursClosePm): void
    {
        $this->hoursClosePm = $hoursClosePm;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function setUpdatedAt(DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
