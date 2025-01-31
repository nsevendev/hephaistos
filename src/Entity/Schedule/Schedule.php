<?php

declare(strict_types=1);

namespace Heph\Entity\Schedule;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Heph\Repository\Schedule\ScheduleRepository;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ScheduleRepository::class)]
class Schedule
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private Uuid $id;

    public function id(): Uuid
    {
        return $this->id;
    }

    #[ORM\Column(type: 'string', name: 'day', nullable: false)]
    private string $day;

    public function day(): string
    {
        return $this->day;
    }

    public function setDay(string $day): void
    {
        $this->day = $day;
        $this->updatedAt = new DateTimeImmutable();
    }

    #[ORM\Column(type: 'string', name: 'hours_open_am', nullable: false)]
    private string $hoursOpenAm;

    public function hoursOpenAm(): string
    {
        return $this->hoursOpenAm;
    }

    public function setHoursOpenAm(string $hoursOpenAm): void
    {
        $this->hoursOpenAm = $hoursOpenAm;
        $this->updatedAt = new DateTimeImmutable();
    }

    #[ORM\Column(type: 'string', name: 'hours_close_am', nullable: false)]
    private string $hoursCloseAm;

    public function hoursCloseAm(): string
    {
        return $this->hoursCloseAm;
    }

    public function setHoursCloseAm(string $hoursCloseAm): void
    {
        $this->hoursCloseAm = $hoursCloseAm;
        $this->updatedAt = new DateTimeImmutable();
    }

    #[ORM\Column(type: 'string', name: 'hours_open_pm', nullable: false)]
    private string $hoursOpenPm;

    public function hoursOpenPm(): string
    {
        return $this->hoursOpenPm;
    }

    public function setHoursOpenPm(string $hoursOpenPm): void
    {
        $this->hoursOpenPm = $hoursOpenPm;
        $this->updatedAt = new DateTimeImmutable();
    }

    #[ORM\Column(type: 'string', name: 'hours_close_pm', nullable: false)]
    private string $hoursClosePm;

    public function hoursClosePm(): string
    {
        return $this->hoursClosePm;
    }

    public function setHoursClosePm(string $hoursClosePm): void
    {
        $this->hoursClosePm = $hoursClosePm;
        $this->updatedAt = new DateTimeImmutable();
    }

    #[ORM\Column(type: 'datetime_immutable', name: 'created_at', nullable: false)]
    private DateTimeImmutable $createdAt;

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    #[ORM\Column(type: 'datetime_immutable', name: 'updated_at', nullable: false)]
    private DateTimeImmutable $updatedAt;

    public function updatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function __construct(
        string $day,
        string $hoursOpenAm,
        string $hoursCloseAm,
        string $hoursOpenPm,
        string $hoursClosePm,
    ) {
        $this->id = Uuid::v7();
        $this->day = $day;
        $this->hoursOpenAm = $hoursOpenAm;
        $this->hoursCloseAm = $hoursCloseAm;
        $this->hoursOpenPm = $hoursOpenPm;
        $this->hoursClosePm = $hoursClosePm;
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = $this->createdAt;
    }
}
