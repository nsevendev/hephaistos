<?php

declare(strict_types=1);

namespace Heph\Infrastructure\ApiResponse\Exception\Custom\EngineRemap;

use Heph\Infrastructure\ApiResponse\Exception\Custom\AbstractApiResponseException;
use Heph\Infrastructure\ApiResponse\Exception\Error\Error;
use Symfony\Component\HttpFoundation\Response;

class EngineRemapInvalidArgumentException extends AbstractApiResponseException
{
    /**
     * @param array<Error>|null $errors
     */
    public function __construct(
        string $getMessage = '',
        int $statusCode = 422,
        ?array $errors = null,
    ) {
        $statusTexts = Response::$statusTexts;

        if ('' === $getMessage && true === array_key_exists($statusCode, $statusTexts)) {
            $getMessage = $statusTexts[$statusCode];
        }

        parent::__construct(
            getMessage: $getMessage,
            statusCode: $statusCode,
            errors: $errors
        );
    }
}
