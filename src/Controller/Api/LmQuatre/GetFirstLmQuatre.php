<?php

declare(strict_types=1);

namespace Heph\Controller\Api\LmQuatre;

use Heph\Infrastructure\ApiResponse\ApiResponseFactory;
use Heph\Infrastructure\Controller\AbstractHephController;
use Heph\Infrastructure\Serializer\HephSerializer;
use Heph\Message\Query\LmQuatre\GetFirstLmQuatreQuery;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsController]
class GetFirstLmQuatre extends AbstractHephController
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
    #[Route(path: '/api/lm-quatre', name: 'heph_api_first_lm_quatre', methods: ['GET'])]
    public function __invoke(
        Request $request,
    ): Response {
        $lmQuatre = $this->handle(new GetFirstLmQuatreQuery());

        return ApiResponseFactory::success(data: $lmQuatre);
    }
}
