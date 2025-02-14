<?php

declare(strict_types=1);

namespace Heph\Infrastructure\Mercure;

use Heph\Infrastructure\ApiResponse\Exception\Custom\Mercure\MercureInvalidArgumentException;
use Heph\Infrastructure\ApiResponse\Exception\Error\Error;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;

readonly class MercureJwtGenerator
{
    public function __construct(private string $mercureSecret) {}

    /**
     * @param array<string> $topics
     *
     * @throws MercureInvalidArgumentException
     */
    public function generatePublisherToken(array $topics = ['*']): string
    {
        if ('' === $this->mercureSecret) {
            throw new MercureInvalidArgumentException(getMessage: 'Mercure secret is not set', errors: [Error::create(key: 'mercure', message: 'Mercure secret is not set')]);
        }

        $config = Configuration::forSymmetricSigner(new Sha256(), InMemory::plainText($this->mercureSecret));

        $token = $config->builder()
            ->withClaim('mercure', [
                'publish' => $topics,
            ])
            ->getToken($config->signer(), $config->signingKey());

        return $token->toString();
    }

    /**
     * @param array<string> $topics
     *
     * @throws MercureInvalidArgumentException
     */
    public function generateSubscriberToken(array $topics = ['*']): string
    {
        if ('' === $this->mercureSecret) {
            throw new MercureInvalidArgumentException(getMessage: 'Mercure secret is not set', errors: [Error::create(key: 'mercure', message: 'Mercure secret is not set')]);
        }

        $config = Configuration::forSymmetricSigner(new Sha256(), InMemory::plainText($this->mercureSecret));

        $token = $config->builder()
            ->withClaim('mercure', [
                'subscribe' => $topics,
            ])
            ->getToken($config->signer(), $config->signingKey());

        return $token->toString();
    }
}
