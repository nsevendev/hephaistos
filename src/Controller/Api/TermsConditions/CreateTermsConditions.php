<?php

declare(strict_types=1);

namespace Heph\Controller\Api\TermsConditions;

use Heph\Entity\TermsConditions\Dto\TermsConditionsCreateDto;
use Heph\Infrastructure\ApiResponse\ApiResponseFactory;
use Heph\Infrastructure\ApiResponse\Exception\Custom\TermsConditions\TermsConditionsInvalidArgumentException;
use Heph\Infrastructure\Controller\AbstractHephController;
use Heph\Message\Command\TermsConditions\CreateTermsConditionsCommand;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;

#[AsController]
class CreateTermsConditions extends AbstractHephController
{
    /**
     * @throws ExceptionInterface
     * @throws TermsConditionsInvalidArgumentException
     * @throws Throwable
     */
    #[Route(path: '/api/terms-conditions', name: 'heph_api_create_terms_conditions', methods: ['POST'])]
    public function __invoke(
        Request $request,
        MessageBusInterface $commandBus,
    ): Response {
        /** @var TermsConditionsCreateDto $dto */
        $dto = $this->deserializeAndValidate(
            data: $request->getContent(),
            dtoClass: TermsConditionsCreateDto::class,
            fnException: fn (array $errors) => new TermsConditionsInvalidArgumentException(
                getMessage: 'Erreur de validation',
                errors: $errors
            )
        );

        $commandBus->dispatch(
            new CreateTermsConditionsCommand(
                termsConditionsCreateDto: $dto,
            )
        );

        return ApiResponseFactory::success(data: ['message' => 'La demande a été prise en compte.']);
    }
}
