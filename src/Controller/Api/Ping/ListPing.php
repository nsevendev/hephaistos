<?php

declare(strict_types=1);

namespace Heph\Controller\Api\Ping;

use Heph\Infrastructure\ApiResponse\ApiResponseFactory;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
class ListPing
{
    #[Route(path: '/api/pings', name: 'heph_api_list_ping', methods: ['GET'])]
    public function index(): Response
    {
        return ApiResponseFactory::success(data: [['ping' => 'ping']]);
    }
}
