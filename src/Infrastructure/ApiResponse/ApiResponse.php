<?php

declare(strict_types=1);

namespace Heph\Infrastructure\ApiResponse;

use Heph\Infrastructure\ApiResponse\Component\ApiResponseData;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseLink;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseMessage;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseMeta;
use Heph\Infrastructure\ApiResponse\Exception\Error\ListError;

final readonly class ApiResponse
{
    public function __construct(
        private int $responseStatusCode,
        private ApiResponseMessage $apiResponseMessage,
        private ApiResponseData $apiResponseData,
        private ListError $listError,
        private ApiResponseLink $apiResponseLink = new ApiResponseLink(),
        private ApiResponseMeta $apiResponseMeta = new ApiResponseMeta(),
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'statusCode' => $this->responseStatusCode,
            'message' => $this->apiResponseMessage->message,
            'data' => $this->apiResponseData->data,
            'errors' => $this->listError->toArray(),
            'meta' => $this->apiResponseMeta->listMetaData(),
            'links' => $this->apiResponseLink->listLinks(),
        ];
    }
}
