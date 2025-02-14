<?php

declare(strict_types=1);

namespace Heph\Infrastructure\ApiResponse\Exception\Custom\EngineRemap;

use Heph\Infrastructure\ApiResponse\Exception\Custom\AbstractApiResponseException;
use Heph\Infrastructure\ApiResponse\Exception\Error\Error;
use Symfony\Component\HttpFoundation\Response;

class EngineRemapNotFoundException extends AbstractApiResponseException
{
    /**
     * @param array<Error>|null $errors
     */
    public function __construct(
        string $getMessage = 'EngineRemap non trouvé',
        int $statusCode = Response::HTTP_NOT_FOUND,
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
