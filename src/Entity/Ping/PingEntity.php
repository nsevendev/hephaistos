<?php

declare(strict_types=1);

namespace Heph\Entity\Ping;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Heph\Entity\Ping\Type\PingId;
use Heph\Repository\Ping\PingEntityRepository;

#[ORM\Entity(repositoryClass: PingEntityRepository::class)]
class PingEntity
{
    #[ORM\Id]
    #[ORM\Column(type: 'app_uid', unique: true)]
    private PingId $id;

    public function id(): PingId
    {
        return $this->id;
    }

    #[ORM\Column]
    private ?int $status;

    public function status(): ?int
    {
        return $this->status;
    }

    #[ORM\Column(length: 255)]
    private ?string $message;

    public function message(): ?string
    {
        return $this->message;
    }

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $createdAt;

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    #[ORM\Column(type: 'datetime_immutable')]
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
        ?int $status,
        ?string $message,
    ) {
        $this->id = PingId::create();
        $this->status = $status;
        $this->message = $message;
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = $this->createdAt;
    }
}
