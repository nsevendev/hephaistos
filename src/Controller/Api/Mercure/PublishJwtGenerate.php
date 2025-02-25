<?php

declare(strict_types=1);

namespace Heph\Controller\Api\Mercure;

use Heph\Infrastructure\ApiResponse\ApiResponseFactory;
use Heph\Infrastructure\ApiResponse\Exception\Custom\Mercure\MercureInvalidArgumentException;
use Heph\Infrastructure\Controller\AbstractHephController;
use Heph\Infrastructure\Mercure\MercureJwtGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
class PublishJwtGenerate extends AbstractHephController
{
    /**
     * @throws MercureInvalidArgumentException
     */
    #[Route('/api/mercure/jwt/pub', name: 'api_mercure_jwt_pub', methods: ['GET'])]
    public function __invoke(MercureJwtGenerator $mercureJwtGenerator): Response
    {
        return ApiResponseFactory::success(data: [
            'tokenMercurePublish' => $mercureJwtGenerator->generatePublisherToken(),
        ]);
    }
}
