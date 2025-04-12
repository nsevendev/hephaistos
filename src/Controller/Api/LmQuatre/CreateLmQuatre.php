<?php

declare(strict_types=1);

namespace Heph\Controller\Api\LmQuatre;

use Heph\Entity\LmQuatre\Dto\LmQuatreCreateDto;
use Heph\Infrastructure\ApiResponse\ApiResponseFactory;
use Heph\Infrastructure\ApiResponse\Exception\Custom\LmQuatre\LmQuatreInvalidArgumentException;
use Heph\Infrastructure\Controller\AbstractHephController;
use Heph\Message\Command\LmQuatre\CreateLmQuatreCommand;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;

#[AsController]
class CreateLmQuatre extends AbstractHephController
{
    /**
     * @throws ExceptionInterface
     * @throws LmQuatreInvalidArgumentException
     * @throws Throwable
     */
    #[Route(path: '/api/lm-quatre', name: 'heph_api_create_lm_quatre', methods: ['POST'])]
    public function __invoke(
        Request $request,
        MessageBusInterface $commandBus,
    ): Response {
        /** @var LmQuatreCreateDto $dto */
        $dto = $this->deserializeAndValidate(
            data: $request->getContent(),
            dtoClass: LmQuatreCreateDto::class,
            fnException: fn(array $errors) => new LmQuatreInvalidArgumentException(
                getMessage: 'Erreur de validation',
                errors: $errors
            )
        );

        $commandBus->dispatch(
            new CreateLmQuatreCommand(
                lmQuatreCreateDto: $dto,
            )
        );

        return ApiResponseFactory::success(data: ['message' => 'La demande a été prise en compte.']);
    }
}
