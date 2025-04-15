<?php

declare(strict_types=1);

namespace Heph\Controller\Api\LmQuatre;

use Heph\Entity\LmQuatre\Dto\LmQuatreUpdateDto;
use Heph\Infrastructure\ApiResponse\ApiResponseFactory;
use Heph\Infrastructure\ApiResponse\Exception\Custom\LmQuatre\LmQuatreInvalidArgumentException;
use Heph\Infrastructure\Controller\AbstractHephController;
use Heph\Message\Command\LmQuatre\UpdateLmQuatreCommand;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;

#[AsController]
class UpdateLmQuatre extends AbstractHephController
{
    /**
     * @throws ExceptionInterface
     * @throws LmQuatreInvalidArgumentException
     * @throws Throwable
     */
    #[Route(path: '/api/lm-quatre/{id}', name: 'heph_api_update_lm_quatre', methods: ['PUT'])]
    public function __invoke(
        string $id,
        Request $request,
        MessageBusInterface $commandBus,
    ): Response {
        /** @var LmQuatreUpdateDto $dto */
        $dto = $this->deserializeAndValidate(
            data: $request->getContent(),
            dtoClass: LmQuatreUpdateDto::class,
            fnException: fn(array $errors) => new LmQuatreInvalidArgumentException(
                getMessage: 'Erreur de validation',
                errors: $errors
            )
        );

        $commandBus->dispatch(
            new UpdateLmQuatreCommand(
                id: $id,
                lmQuatreUpdateDto: $dto,
            )
        );

        return ApiResponseFactory::success(data: ['message' => 'La mise à jour a été prise en compte.']);
    }
}
