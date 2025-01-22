<?php

declare(strict_types=1);

namespace Heph\Entity\Ping\Dto;

use Heph\Entity\Ping\ValueObject\PingMessage;
use Heph\Entity\Ping\ValueObject\PingStatus;

class PingEntityRequestDto
{
    public function __construct(
        private PingStatus $status,
        private PingMessage $message,
    )
    {
    }

    public static function fromTo(string $status, string $message): self
    {
        return new self(
            status: PingStatus::fromString($status),
            message: PingMessage::fromString($message),
        );
    }

    public function status(): int
    {
        return $this->status->value();
    }

    public function message(): string
    {
        return $this->message->value();
    }
}
