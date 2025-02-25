<?php

declare(strict_types=1);

namespace Heph\Controller\Api\EngineRemap;

use Heph\Infrastructure\ApiResponse\ApiResponseFactory;
use Heph\Infrastructure\Controller\AbstractHephController;
use Heph\Infrastructure\Serializer\HephSerializer;
use Heph\Message\Query\EngineRemap\GetFirstEngineRemapQuery;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsController]
class GetFirstEngineRemap extends AbstractHephController
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
    #[Route(path: '/api/engine-remap', name: 'heph_api_first_engine_remap', methods: ['GET'])]
    public function __invoke(Request $request): Response
    {
        $firstEngineRemap = $this->handle(new GetFirstEngineRemapQuery());

        return ApiResponseFactory::success(data: $firstEngineRemap ?? null);
    }
}
