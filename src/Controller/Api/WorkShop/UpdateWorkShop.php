<?php

declare(strict_types=1);

namespace Heph\Controller\Api\WorkShop;

use Heph\Entity\WorkShop\Dto\WorkShopUpdateDto;
use Heph\Infrastructure\ApiResponse\ApiResponseFactory;
use Heph\Infrastructure\ApiResponse\Exception\Custom\WorkShop\WorkShopInvalidArgumentException;
use Heph\Infrastructure\Controller\AbstractHephController;
use Heph\Message\Command\WorkShop\UpdateWorkShopCommand;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;

#[AsController]
class UpdateWorkShop extends AbstractHephController
{
    /**
     * @throws ExceptionInterface
     * @throws WorkShopInvalidArgumentException
     * @throws Throwable
     */
    #[Route(path: '/api/workshop/{id}', name: 'heph_api_update_workshop', methods: ['PUT'])]
    public function __invoke(
        string $id,
        Request $request,
        MessageBusInterface $commandBus,
    ): Response {
        /** @var WorkShopUpdateDto $dto */
        $dto = $this->deserializeAndValidate(
            data: $request->getContent(),
            dtoClass: WorkShopUpdateDto::class,
            fnException: fn (array $errors) => new WorkShopInvalidArgumentException(
                getMessage: 'Erreur de validation',
                errors: $errors
            )
        );

        $commandBus->dispatch(
            new UpdateWorkShopCommand(
                id: $id,
                workShopUpdateDto: $dto,
            )
        );

        return ApiResponseFactory::success(data: ['message' => 'La mise à jour a été prise en compte.']);
    }
}
