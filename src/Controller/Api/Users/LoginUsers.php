<?php

declare(strict_types=1);

namespace Heph\Controller\Api\Users;

use Heph\Entity\Users\Dto\UsersLoginDto;
use Heph\Infrastructure\ApiResponse\ApiResponseFactory;
use Heph\Infrastructure\ApiResponse\Exception\Custom\Users\UsersInvalidArgumentException;
use Heph\Infrastructure\ApiResponse\Exception\Error\Error;
use Heph\Infrastructure\Controller\AbstractHephController;
use Heph\Message\Query\Users\LoginUsersQuery;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;

#[AsController]
class LoginUsers extends AbstractHephController
{
    /**
     * @throws ExceptionInterface
     * @throws UsersInvalidArgumentException
     * @throws Throwable
     */
    #[Route(path: '/api/login', name: 'heph_api_login', methods: ['POST'])]
    public function __invoke(
        Request $request,
        MessageBusInterface $queryBus,
    ): Response {
        /** @var UsersLoginDto $dto */
        $dto = $this->deserializeAndValidate(
            data: $request->getContent(),
            dtoClass: UsersLoginDto::class,
            fnException: fn (array $errors) => new UsersInvalidArgumentException(
                getMessage: 'Erreur de validation',
                errors: $errors
            )
        );

        /** @var string|null $token */
        $token = $queryBus->dispatch(
            new LoginUsersQuery(
                usersLoginDto: $dto,
            )
        )->last(HandledStamp::class)?->getResult();

        if (!$token) {
            throw new UsersInvalidArgumentException(getMessage: 'Nom d\'utilisateur ou mot de passe incorrect.', errors: [Error::create('login', 'Identifiants invalides.')]);
        }

        return ApiResponseFactory::success(data: ['token' => $token]);
    }
}
