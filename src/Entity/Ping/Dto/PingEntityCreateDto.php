<?php

declare(strict_types=1);

namespace Heph\Entity\Ping\Dto;

use Heph\Entity\Ping\ValueObject\PingMessage;
use Heph\Entity\Ping\ValueObject\PingStatus;
use Symfony\Component\Validator\Constraints as Assert;

readonly class PingEntityCreateDto
{
    public function __construct(
        #[Assert\Valid]
        private PingStatus $status,
        #[Assert\Valid]
        private PingMessage $message,
    ) {}

    public static function new(int $status, string $message): self
    {
        return new self(
            status: PingStatus::fromValue($status),
            message: PingMessage::fromValue($message),
        );
    }

    public function status(): PingStatus
    {
        return $this->status;
    }

    public function message(): PingMessage
    {
        return $this->message;
    }
}
