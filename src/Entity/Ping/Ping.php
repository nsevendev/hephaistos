<?php

declare(strict_types=1);

namespace Heph\Entity\Ping;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Heph\Entity\Ping\ValueObject\PingMessage;
use Heph\Entity\Ping\ValueObject\PingStatus;
use Heph\Repository\Ping\PingRepository;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: PingRepository::class)]
class Ping
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private Uuid $id;

    #[ORM\Column(name: 'created_at', type: 'datetime_immutable', nullable: false)]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(name: 'updated_at', type: 'datetime_immutable', nullable: false)]
    private DateTimeImmutable $updatedAt;

    public function __construct(
        #[ORM\Column(name: 'status', type: 'app_ping_status', nullable: false)]
        private PingStatus $status,
        #[ORM\Column(name: 'message', type: 'app_ping_message', length: 255, nullable: false)]
        private PingMessage $message,
    ) {
        $this->id = Uuid::v7();
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = $this->createdAt;
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function status(): PingStatus
    {
        return $this->status;
    }

    public function message(): PingMessage
    {
        return $this->message;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
