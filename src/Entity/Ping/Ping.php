<?php

declare(strict_types=1);

namespace Heph\Entity\Ping;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Heph\Entity\Ping\Type\PingId;
use Heph\Repository\Ping\PingRepository;

#[ORM\Entity(repositoryClass: PingRepository::class)]
class Ping
{
    #[ORM\Id]
    #[ORM\Column(type: 'app_uid', name: 'id', unique: true)]
    private PingId $id;

    public function id(): PingId
    {
        return $this->id;
    }

    #[ORM\Column(name: 'status', nullable: false)]
    private int $status;

    public function status(): int
    {
        return $this->status;
    }

    #[ORM\Column(name: 'message', nullable: false, length: 255)]
    private string $message;

    public function message(): string
    {
        return $this->message;
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
        int $status,
        string $message,
    ) {
        $this->id = PingId::create();
        $this->status = $status;
        $this->message = $message;
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = $this->createdAt;
    }
}
