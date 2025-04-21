<?php

declare(strict_types=1);

namespace Heph\Controller\Api\TermsConditions;

use Heph\Infrastructure\ApiResponse\ApiResponseFactory;
use Heph\Infrastructure\Controller\AbstractHephController;
use Heph\Infrastructure\Serializer\HephSerializer;
use Heph\Message\Query\TermsConditions\GetListTermsConditionsQuery;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsController]
class ListTermsConditions extends AbstractHephController
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
    #[Route(path: '/api/list-terms-conditions', name: 'heph_api_list_terms_conditions', methods: ['GET'])]
    public function __invoke(
        Request $request,
    ): Response {
        $listTermsConditions = $this->handle(new GetListTermsConditionsQuery());

        return ApiResponseFactory::success(data: $listTermsConditions);
    }
}
