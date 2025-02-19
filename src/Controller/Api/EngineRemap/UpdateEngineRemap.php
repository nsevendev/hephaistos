<?php

declare(strict_types=1);

namespace Heph\Controller\Api\EngineRemap;

use Heph\Entity\EngineRemap\Dto\EngineRemapUpdateDto;
use Heph\Infrastructure\ApiResponse\ApiResponseFactory;
use Heph\Infrastructure\ApiResponse\Exception\Custom\EngineRemap\EngineRemapInvalidArgumentException;
use Heph\Infrastructure\Controller\AbstractHephController;
use Heph\Message\Command\EngineRemap\UpdateEngineRemapCommand;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;

#[AsController]
class UpdateEngineRemap extends AbstractHephController
{
    /**
     * @throws ExceptionInterface
     * @throws EngineRemapInvalidArgumentException
     * @throws Throwable
     */
    #[Route(path: '/api/engine-remap', name: 'heph_api_update_engine_remap', methods: ['PUT'])]
    public function __invoke(
        Request $request,
        MessageBusInterface $commandBus,
    ): Response {
        /** @var EngineRemapUpdateDto $dto */
        $dto = $this->deserializeAndValidate(
            data: $request->getContent(),
            dtoClass: EngineRemapUpdateDto::class,
            fnException: fn (array $errors) => new EngineRemapInvalidArgumentException(
                getMessage: 'Erreur de validation',
                errors: $errors
            )
        );
        var_dump("LAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA : ", $dto);

        var_dump('Message envoyé : ', $dto);
        $commandBus->dispatch(
            new UpdateEngineRemapCommand(
                engineRemapUpdateDto: $dto,
            )
        );

        return ApiResponseFactory::success(data: ['message' => 'La mise à jour a été prise en compte.']);
    }
}
