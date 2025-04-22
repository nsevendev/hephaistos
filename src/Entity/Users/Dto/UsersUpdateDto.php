<?php

declare(strict_types=1);

namespace Heph\Entity\Users\Dto;

use Symfony\Component\Validator\Constraints as Assert;

readonly class UsersUpdateDto
{
    public function __construct(
        #[Assert\NotBlank(message: 'Le username est requis.')]
        #[Assert\Length(max: 255, maxMessage: 'Le username doit contenir au plus {{ limit }} caractères.')]
        public string $username,
        #[Assert\NotBlank(message: 'Le password est requis.')]
        #[Assert\Length(max: 255, maxMessage: 'Le password doit contenir au plus {{ limit }} caractères.')]
        public string $password,
        #[Assert\NotBlank(message: 'Le role est requis.')]
        #[Assert\Choice(choices: ['ROLE_ADMIN', 'ROLE_EMPLOYEE'], message: 'Le role doit être parmi {{ choices }}')]
        public string $role,
    ) {}

    public static function new(string $username, string $password, string $role): self
    {
        return new self(
            username: $username,
            password: $password,
            role: $role,
        );
    }
}
