<?php

declare(strict_types=1);

namespace Heph\Controller\Api\WorkShop;

use Heph\Entity\WorkShop\Dto\WorkShopCreateDto;
use Heph\Infrastructure\ApiResponse\ApiResponseFactory;
use Heph\Infrastructure\ApiResponse\Exception\Custom\WorkShop\WorkShopInvalidArgumentException;
use Heph\Infrastructure\Controller\AbstractHephController;
use Heph\Message\Command\WorkShop\CreateWorkShopCommand;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;

#[AsController]
class CreateWorkShop extends AbstractHephController
{
    /**
     * @throws ExceptionInterface
     * @throws WorkShopInvalidArgumentException
     * @throws Throwable
     */
    #[Route(path: '/api/workshop', name: 'heph_api_create_workshop', methods: ['POST'])]
    public function __invoke(
        Request $request,
        MessageBusInterface $commandBus,
    ): Response {
        /** @var WorkShopCreateDto $dto */
        $dto = $this->deserializeAndValidate(
            data: $request->getContent(),
            dtoClass: WorkShopCreateDto::class,
            fnException: fn (array $errors) => new WorkShopInvalidArgumentException(
                getMessage: 'Erreur de validation',
                errors: $errors
            )
        );

        $commandBus->dispatch(
            new CreateWorkShopCommand(
                workShopCreateDto: $dto,
            )
        );

        return ApiResponseFactory::success(data: ['message' => 'La demande a été prise en compte.']);
    }
}
