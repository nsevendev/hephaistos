<?php

declare(strict_types=1);

namespace Heph\Controller\Api\TermsConditionsArticle;

use Heph\Entity\TermsConditionsArticle\Dto\TermsConditionsArticleUpdateDto;
use Heph\Infrastructure\ApiResponse\ApiResponseFactory;
use Heph\Infrastructure\ApiResponse\Exception\Custom\TermsConditionsArticle\TermsConditionsArticleInvalidArgumentException;
use Heph\Infrastructure\Controller\AbstractHephController;
use Heph\Message\Command\TermsConditionsArticle\UpdateTermsConditionsArticleCommand;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;

#[AsController]
class UpdateTermsConditionsArticle extends AbstractHephController
{
    /**
     * @throws ExceptionInterface
     * @throws TermsConditionsArticleInvalidArgumentException
     * @throws Throwable
     */
    #[Route(path: '/api/terms-conditions-article/{id}', name: 'heph_api_update_terms_conditions_article', methods: ['PUT'])]
    public function __invoke(
        string $id,
        Request $request,
        MessageBusInterface $commandBus,
    ): Response {
        /** @var TermsConditionsArticleUpdateDto $dto */
        $dto = $this->deserializeAndValidate(
            data: $request->getContent(),
            dtoClass: TermsConditionsArticleUpdateDto::class,
            fnException: fn(array $errors) => new TermsConditionsArticleInvalidArgumentException(
                getMessage: 'Erreur de validation',
                errors: $errors
            )
        );

        $commandBus->dispatch(
            new UpdateTermsConditionsArticleCommand(
                id: $id,
                termsConditionsArticleUpdateDto: $dto,
            )
        );

        return ApiResponseFactory::success(data: ['message' => 'La mise à jour a été prise en compte.']);
    }
}
