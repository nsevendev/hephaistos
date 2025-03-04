<?php

declare(strict_types=1);

namespace Heph\Entity\Ping;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Heph\Repository\Ping\PingRepository;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: PingRepository::class)]
class Ping
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private Uuid $id;

    public function id(): Uuid
    {
        return $this->id;
    }

    #[ORM\Column(name: 'status', type: 'string', nullable: false)]
    private int $status;

    public function status(): int
    {
        return $this->status;
    }

    #[ORM\Column(name: 'message', type: 'string', length: 255, nullable: false)]
    private string $message;

    public function message(): string
    {
        return $this->message;
    }

    #[ORM\Column(name: 'created_at', type: 'datetime_immutable', nullable: false)]
    private DateTimeImmutable $createdAt;

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    #[ORM\Column(name: 'updated_at', type: 'datetime_immutable', nullable: false)]
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
        $this->id = Uuid::v7();
        $this->status = $status;
        $this->message = $message;
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = $this->createdAt;
    }
}
