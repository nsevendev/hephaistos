<?php

declare(strict_types=1);

namespace Heph\Controller\Api\CarSales;

use Heph\Entity\CarSales\Dto\CarSalesCreateDto;
use Heph\Infrastructure\ApiResponse\ApiResponseFactory;
use Heph\Infrastructure\ApiResponse\Exception\Custom\CarSales\CarSalesInvalidArgumentException;
use Heph\Infrastructure\Controller\AbstractHephController;
use Heph\Message\Command\CarSales\CreateCarSalesCommand;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;

#[AsController]
class CreateCarSales extends AbstractHephController
{
    /**
     * @throws ExceptionInterface
     * @throws CarSalesInvalidArgumentException
     * @throws Throwable
     */
    #[Route(path: '/api/car-sales', name: 'heph_api_create_car_sales', methods: ['POST'])]
    public function __invoke(
        Request $request,
        MessageBusInterface $commandBus,
    ): Response {
        /** @var CarSalesCreateDto $dto */
        $dto = $this->deserializeAndValidate(
            data: $request->getContent(),
            dtoClass: CarSalesCreateDto::class,
            fnException: fn(array $errors) => new CarSalesInvalidArgumentException(
                getMessage: 'Erreur de validation',
                errors: $errors
            )
        );

        $commandBus->dispatch(
            new CreateCarSalesCommand(
                carSalesCreateDto: $dto,
            )
        );

        return ApiResponseFactory::success(data: ['message' => 'La demande a été prise en compte.']);
    }
}
