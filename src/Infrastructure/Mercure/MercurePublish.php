<?php

declare(strict_types=1);

namespace Heph\Infrastructure\Mercure;

use Heph\Infrastructure\ApiResponse\Exception\Custom\Mercure\MercureInvalidArgumentException;
use Heph\Infrastructure\ApiResponse\Exception\Error\Error;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

class MercurePublish
{
    public function __construct(private HubInterface $hub) {}

    /**
     * @param array<string, mixed> $data
     *
     * @throws MercureInvalidArgumentException
     */
    public function publish(string $topic, array $data): void
    {
        $json = json_encode($data);

        if (false === $json) {
            throw new MercureInvalidArgumentException(getMessage: 'Données invalides à publier', errors: [Error::create(key: 'data', message: 'Données invalides à publier')]);
        }

        $update = new Update(
            topics: $topic,
            data: $json,
            private: true
        );
        $this->hub->publish($update);
    }
}
