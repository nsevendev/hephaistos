<?php

declare(strict_types=1);

namespace Heph\Entity\Ping\Dto;

use Symfony\Component\Validator\Constraints as Assert;

readonly class PingCreateDto
{
    public function __construct(
        #[Assert\NotBlank(message: 'Le status est requis.')]
        #[Assert\Choice(choices: [200], message: 'Le status doit être de {{ choices }}')]
        public int $status,
        #[Assert\NotBlank(message: 'Le status est requis.')]
        #[Assert\Length(max: 255, maxMessage: 'Le status doit contenir au plus {{ limit }} caractères.')]
        public string $message,
    ) {}

    public static function new(int $status, string $message): self
    {
        return new self(
            status: $status,
            message: $message,
        );
    }
}
