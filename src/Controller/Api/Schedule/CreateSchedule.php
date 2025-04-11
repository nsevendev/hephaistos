<?php

declare(strict_types=1);

namespace Heph\Controller\Api\Schedule;

use Heph\Entity\Schedule\Dto\ScheduleCreateDto;
use Heph\Infrastructure\ApiResponse\ApiResponseFactory;
use Heph\Infrastructure\ApiResponse\Exception\Custom\Schedule\ScheduleInvalidArgumentException;
use Heph\Infrastructure\Controller\AbstractHephController;
use Heph\Message\Command\Schedule\CreateScheduleCommand;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;

#[AsController]
class CreateSchedule extends AbstractHephController
{
    /**
     * @throws ExceptionInterface
     * @throws ScheduleInvalidArgumentException
     * @throws Throwable
     */
    #[Route(path: '/api/schedule', name: 'heph_api_create_schedule', methods: ['POST'])]
    public function __invoke(
        Request $request,
        MessageBusInterface $commandBus,
    ): Response {
        /** @var ScheduleCreateDto $dto */
        $dto = $this->deserializeAndValidate(
            data: $request->getContent(),
            dtoClass: ScheduleCreateDto::class,
            fnException: fn(array $errors) => new ScheduleInvalidArgumentException(
                getMessage: 'Erreur de validation',
                errors: $errors
            )
        );

        $commandBus->dispatch(
            new CreateScheduleCommand(
                scheduleEntityCreateDto: $dto
            )
        );

        return ApiResponseFactory::success(data: ['message' => 'La demande a été prise en compte.']);
    }
}
