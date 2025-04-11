<?php

declare(strict_types=1);

namespace Heph\Controller\Api\Schedule;

use Heph\Entity\Schedule\Dto\ScheduleUpdateDto;
use Heph\Infrastructure\ApiResponse\ApiResponseFactory;
use Heph\Infrastructure\ApiResponse\Exception\Custom\Schedule\ScheduleInvalidArgumentException;
use Heph\Infrastructure\Controller\AbstractHephController;
use Heph\Message\Command\Schedule\UpdateScheduleCommand;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;

#[AsController]
class UpdateSchedule extends AbstractHephController
{
    /**
     * @throws ExceptionInterface
     * @throws ScheduleInvalidArgumentException
     * @throws Throwable
     */
    #[Route(path: '/api/schedule/{id}', name: 'heph_api_update_schedule', methods: ['PUT'])]
    public function __invoke(
        string $id,
        Request $request,
        MessageBusInterface $commandBus,
    ): Response {
        /** @var ScheduleUpdateDto $dto */
        $dto = $this->deserializeAndValidate(
            data: $request->getContent(),
            dtoClass: ScheduleUpdateDto::class,
            fnException: fn(array $errors) => new ScheduleInvalidArgumentException(
                getMessage: 'Erreur de validation',
                errors: $errors
            )
        );

        $commandBus->dispatch(
            new UpdateScheduleCommand(
                id: $id,
                scheduleUpdateDto: $dto,
            )
        );

        return ApiResponseFactory::success(data: ['message' => 'La mise à jour a été prise en compte.']);
    }
}
