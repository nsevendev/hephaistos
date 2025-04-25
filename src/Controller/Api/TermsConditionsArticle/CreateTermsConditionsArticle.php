<?php

declare(strict_types=1);

namespace Heph\Controller\Api\TermsConditionsArticle;

use Heph\Entity\TermsConditionsArticle\Dto\TermsConditionsArticleCreateDto;
use Heph\Infrastructure\ApiResponse\ApiResponseFactory;
use Heph\Infrastructure\ApiResponse\Exception\Custom\TermsConditionsArticle\TermsConditionsArticleInvalidArgumentException;
use Heph\Infrastructure\Controller\AbstractHephController;
use Heph\Message\Command\TermsConditionsArticle\CreateTermsConditionsArticleCommand;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;

#[AsController]
class CreateTermsConditionsArticle extends AbstractHephController
{
    /**
     * @throws ExceptionInterface
     * @throws TermsConditionsArticleInvalidArgumentException
     * @throws Throwable
     */
    #[Route(path: '/api/terms-conditions-article', name: 'heph_api_create_terms_conditions_article', methods: ['POST'])]
    public function __invoke(
        Request $request,
        MessageBusInterface $commandBus,
    ): Response {
        /** @var TermsConditionsArticleCreateDto $dto */
        $dto = $this->deserializeAndValidate(
            data: $request->getContent(),
            dtoClass: TermsConditionsArticleCreateDto::class,
            fnException: fn (array $errors) => new TermsConditionsArticleInvalidArgumentException(
                getMessage: 'Erreur de validation',
                errors: $errors
            )
        );

        $commandBus->dispatch(
            new CreateTermsConditionsArticleCommand(
                termsConditionsArticleCreateDto: $dto,
            )
        );

        return ApiResponseFactory::success(data: ['message' => 'La demande a été prise en compte.']);
    }
}
