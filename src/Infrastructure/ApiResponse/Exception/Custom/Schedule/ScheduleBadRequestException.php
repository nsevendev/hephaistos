<?php

declare(strict_types=1);

namespace Heph\Infrastructure\ApiResponse\Exception\Custom\Schedule;

use Heph\Infrastructure\ApiResponse\Exception\Custom\AbstractApiResponseException;
use Heph\Infrastructure\ApiResponse\Exception\Error\Error;
use Symfony\Component\HttpFoundation\Response;

class ScheduleBadRequestException extends AbstractApiResponseException
{
    /**
     * @param array<Error>|null $errors
     */
    public function __construct(
        string $getMessage = '',
        int $statusCode = Response::HTTP_BAD_REQUEST,
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