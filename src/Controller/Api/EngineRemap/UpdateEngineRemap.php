<?php

declare(strict_types=1);

namespace Heph\Controller\Api\EngineRemap;

use Heph\Entity\EngineRemap\Dto\EngineRemapUpdateDto;
use Heph\Infrastructure\ApiResponse\ApiResponseFactory;
use Heph\Message\Command\EngineRemap\UpdateEngineRemapCommand;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
class UpdateEngineRemap
{
    #[Route(path: '/api/engine-remap', name: 'heph_api_update_engine_remap', methods: ['PUT'])]
    public function __invoke(
        Request $request,
        MessageBusInterface $commandBus
    ): Response {
        $data = json_decode($request->getContent(), true);

        $dto = EngineRemapUpdateDto::fromArray($data);
        $commandBus->dispatch(new UpdateEngineRemapCommand($dto));

        return ApiResponseFactory::success(data: ['message' => 'Mise à jour en cours...']);
    }
}
