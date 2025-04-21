<?php

declare(strict_types=1);

namespace Heph\Controller\Api\TermsConditions;

use Heph\Entity\TermsConditions\Dto\TermsConditionsUpdateDto;
use Heph\Infrastructure\ApiResponse\ApiResponseFactory;
use Heph\Infrastructure\ApiResponse\Exception\Custom\TermsConditions\TermsConditionsInvalidArgumentException;
use Heph\Infrastructure\Controller\AbstractHephController;
use Heph\Message\Command\TermsConditions\UpdateTermsConditionsCommand;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;

#[AsController]
class UpdateTermsConditions extends AbstractHephController
{
    /**
     * @throws ExceptionInterface
     * @throws TermsConditionsInvalidArgumentException
     * @throws Throwable
     */
    #[Route(path: '/api/terms-conditions/{id}', name: 'heph_api_update_terms_conditions', methods: ['PUT'])]
    public function __invoke(
        string $id,
        Request $request,
        MessageBusInterface $commandBus,
    ): Response {
        /** @var TermsConditionsUpdateDto $dto */
        $dto = $this->deserializeAndValidate(
            data: $request->getContent(),
            dtoClass: TermsConditionsUpdateDto::class,
            fnException: fn(array $errors) => new TermsConditionsInvalidArgumentException(
                getMessage: 'Erreur de validation',
                errors: $errors
            )
        );

        $commandBus->dispatch(
            new UpdateTermsConditionsCommand(
                id: $id,
                termsConditionsUpdateDto: $dto,
            )
        );

        return ApiResponseFactory::success(data: ['message' => 'La mise à jour a été prise en compte.']);
    }
}
