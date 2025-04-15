<?php

declare(strict_types=1);

namespace Heph\Controller\Api\Users;

use Heph\Infrastructure\ApiResponse\ApiResponseFactory;
use Heph\Infrastructure\Controller\AbstractHephController;
use Heph\Infrastructure\Serializer\HephSerializer;
use Heph\Message\Query\Users\GetUsersByIdQuery;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsController]
class GetUsersById extends AbstractHephController
{
    use HandleTrait;

    public function __construct(
        HephSerializer $serializer,
        ValidatorInterface $validator,
        /** @phpstan-ignore-next-line */
        private MessageBusInterface $messageBus,
    ) {
        parent::__construct($serializer, $validator);
    }

    /**
     * @throws ExceptionInterface
     */
    #[Route(path: '/api/users/{id}', name: 'heph_api_users_by_id', methods: ['GET'])]
    public function __invoke(
        string $id,
        Request $request,
    ): Response {
        $users = $this->handle(new GetUsersByIdQuery($id));

        return ApiResponseFactory::success(data: $users);
    }
}
