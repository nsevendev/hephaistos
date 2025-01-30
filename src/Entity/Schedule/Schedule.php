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

    #[ORM\Column(type: 'string', name: 'hours_start', nullable: false)]
    private string $hoursStart;

    public function hoursStart(): string
    {
        return $this->hoursStart;
    }

    public function setHoursStart(string $hoursStart): void
    {
        $this->hoursStart = $hoursStart;
        $this->updatedAt = new DateTimeImmutable();
    }

    #[ORM\Column(type: 'string', name: 'hours_end', nullable: false)]
    private string $hoursEnd;

    public function hoursEnd(): string
    {
        return $this->hoursEnd;
    }

    public function setHoursEnd(string $hoursEnd): void
    {
        $this->hoursEnd = $hoursEnd;
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
        string $hoursStart,
        string $hoursEnd,
    ) {
        $this->id = Uuid::v7();
        $this->day = $day;
        $this->hoursStart = $hoursStart;
        $this->hoursEnd = $hoursEnd;
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = $this->createdAt;
    }
}
