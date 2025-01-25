<?php

declare(strict_types=1);

namespace Heph\Controller\Api\Ping;

use Heph\Entity\Ping\Dto\PingEntityCreateDto;
use Heph\Infrastructure\ApiResponse\ApiResponseFactory;
use Heph\Infrastructure\ApiResponse\Exception\Custom\Ping\PingInvalidArgumentException;
use Heph\Infrastructure\Controller\AbstractHephController;
use Heph\Message\Command\Ping\CreatePingEntityCommand;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;

#[AsController]
class CreatePing extends AbstractHephController
{
    /**
     * @throws ExceptionInterface
     * @throws PingInvalidArgumentException
     * @throws Throwable
     */
    #[Route(path: '/api/ping', name: 'heph_api_create_ping', methods: ['POST'])]
    public function __invoke(
        Request $request,
        MessageBusInterface $commandBus,
    ): Response {
        /** @var PingEntityCreateDto $dto */
        $dto = $this->deserializeAndValidate(
            data: $request->getContent(),
            dtoClass: PingEntityCreateDto::class,
            fnException: fn (array $errors) => new PingInvalidArgumentException(
                getMessage: 'Erreur de validation',
                errors: $errors
            )
        );
        $commandBus->dispatch(
            new CreatePingEntityCommand(
                pingEntityCreateDto: $dto
            )
        );

        return ApiResponseFactory::success(data: ['message' => 'La demande a été prise en compte.']);
    }
}
