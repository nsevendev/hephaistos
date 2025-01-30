<?php

declare(strict_types=1);

namespace Heph\Controller\Api\Ping;

use Heph\Infrastructure\ApiResponse\ApiResponseFactory;
use Heph\Infrastructure\Controller\AbstractHephController;
use Heph\Infrastructure\Serializer\HephSerializer;
use Heph\Message\Query\Ping\GetListPingQuery;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsController]
class ListPing extends AbstractHephController
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
    #[Route(path: '/api/list-ping', name: 'heph_api_list_ping', methods: ['GET'])]
    public function __invoke(
        Request $request,
    ): Response {
        $listPing = $this->handle(new GetListPingQuery());

        return ApiResponseFactory::success(data: $listPing);
    }
}
