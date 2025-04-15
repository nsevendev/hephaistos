<?php

declare(strict_types=1);

namespace Heph\Message\Command\Users;

class UpdateUsersCommand
{
    public function __construct(
        public readonly string $id,
    ) {}
}
