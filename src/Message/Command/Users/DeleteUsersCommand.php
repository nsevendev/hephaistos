<?php

declare(strict_types=1);

namespace Heph\Message\Command\Users;

class DeleteUsersCommand
{
    public function __construct(
        public string $id,
    ) {}
}
