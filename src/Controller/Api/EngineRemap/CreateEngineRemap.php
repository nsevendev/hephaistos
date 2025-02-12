<?php

declare(strict_types=1);

namespace Heph\Controller\Api\EngineRemap;

use Heph\Entity\EngineRemap\Dto\EngineRemapCreateDto;
use Heph\Infrastructure\ApiResponse\ApiResponseFactory;
use Heph\Infrastructure\ApiResponse\Exception\Custom\EngineRemap\EngineRemapInvalidArgumentException;
use Heph\Infrastructure\Controller\AbstractHephController;
use Heph\Message\Command\EngineRemap\CreateEngineRemapCommand;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;

#[AsController]
class CreateEngineRemap extends AbstractHephController
{
    /**
     * @throws ExceptionInterface
     * @throws EngineRemapInvalidArgumentException
     * @throws Throwable
     */
    #[Route(path: '/api/engine-remap', name: 'heph_api_create_engine_remap', methods: ['POST'])]
    public function __invoke(
        Request $request,
        MessageBusInterface $commandBus,
    ): Response {
        /** @var EngineRemapCreateDto $dto */
        $dto = $this->deserializeAndValidate(
            data: $request->getContent(),
            dtoClass: EngineRemapCreateDto::class,
            fnException: fn (array $errors) => new EngineRemapInvalidArgumentException(
                getMessage: 'Erreur de validation',
                errors: $errors
            )
        );
        
        $commandBus->dispatch(
            new CreateEngineRemapCommand(
                engineRemapCreateDto: $dto,
            )
        );

        return ApiResponseFactory::success(data: ['message' => 'La demande a été prise en compte.']);
    }
}