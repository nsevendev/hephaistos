<?php

declare(strict_types=1);

namespace Heph\Controller\Api\CarSales;

use Heph\Entity\CarSales\Dto\CarSalesUpdateDto;
use Heph\Infrastructure\ApiResponse\ApiResponseFactory;
use Heph\Infrastructure\ApiResponse\Exception\Custom\CarSales\CarSalesInvalidArgumentException;
use Heph\Infrastructure\Controller\AbstractHephController;
use Heph\Message\Command\CarSales\UpdateCarSalesCommand;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;

#[AsController]
class UpdateCarSales extends AbstractHephController
{
    /**
     * @throws ExceptionInterface
     * @throws CarSalesInvalidArgumentException
     * @throws Throwable
     */
    #[Route(path: '/api/car-sales/{id}', name: 'heph_api_update_car_sales', methods: ['PUT'])]
    public function __invoke(
        string $id,
        Request $request,
        MessageBusInterface $commandBus,
    ): Response {
        /** @var CarSalesUpdateDto $dto */
        $dto = $this->deserializeAndValidate(
            data: $request->getContent(),
            dtoClass: CarSalesUpdateDto::class,
            fnException: fn(array $errors) => new CarSalesInvalidArgumentException(
                getMessage: 'Erreur de validation',
                errors: $errors
            )
        );

        $commandBus->dispatch(
            new UpdateCarSalesCommand(
                id: $id,
                carSalesUpdateDto: $dto,
            )
        );

        return ApiResponseFactory::success(data: ['message' => 'La mise à jour a été prise en compte.']);
    }
}
