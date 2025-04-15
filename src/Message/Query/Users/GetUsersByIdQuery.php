<?php

declare(strict_types=1);

namespace Heph\Message\Query\Users;

class GetUsersByIdQuery
{
    public function __construct(
        public readonly string $id,
    ) {}
}
