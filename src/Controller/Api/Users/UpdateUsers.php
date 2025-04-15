<?php

declare(strict_types=1);

namespace Heph\Controller\Api\Users;

use Heph\Entity\Users\Dto\UsersUpdateDto;
use Heph\Infrastructure\ApiResponse\ApiResponseFactory;
use Heph\Infrastructure\ApiResponse\Exception\Custom\Users\UsersInvalidArgumentException;
use Heph\Infrastructure\Controller\AbstractHephController;
use Heph\Message\Command\Users\UpdateUsersCommand;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;

#[AsController]
class UpdateUsers extends AbstractHephController
{
    /**
     * @throws ExceptionInterface
     * @throws UsersInvalidArgumentException
     * @throws Throwable
     */
    #[Route(path: '/api/users/{id}', name: 'heph_api_update_users', methods: ['PUT'])]
    public function __invoke(
        string $id,
        Request $request,
        MessageBusInterface $commandBus,
    ): Response {
        /** @var UsersUpdateDto $dto */
        $dto = $this->deserializeAndValidate(
            data: $request->getContent(),
            dtoClass: UsersUpdateDto::class,
            fnException: fn (array $errors) => new UsersInvalidArgumentException(
                getMessage: 'Erreur de validation',
                errors: $errors
            )
        );

        $commandBus->dispatch(
            new UpdateUsersCommand(
                id: $id,
                usersUpdateDto: $dto,
            )
        );

        return ApiResponseFactory::success(data: ['message' => 'La mise à jour a été prise en compte.']);
    }
}
