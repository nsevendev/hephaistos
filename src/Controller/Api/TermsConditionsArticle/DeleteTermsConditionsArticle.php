<?php

declare(strict_types=1);

namespace Heph\Controller\Api\TermsConditionsArticle;

use Heph\Infrastructure\ApiResponse\ApiResponseFactory;
use Heph\Infrastructure\Controller\AbstractHephController;
use Heph\Message\Command\TermsConditionsArticle\DeleteTermsConditionsArticleCommand;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
class DeleteTermsConditionsArticle extends AbstractHephController
{
    /**
     * @throws ExceptionInterface
     */
    #[Route(path: '/api/terms-conditions-article/{id}', name: 'heph_api_delete_terms_conditions_article', methods: ['DELETE'])]
    public function __invoke(Request $request, MessageBusInterface $commandBus): Response
    {
        /** @var string $id */
        $id = $request->get('id');

        $commandBus->dispatch(
            new DeleteTermsConditionsArticleCommand(
                id: $id
            )
        );

        return ApiResponseFactory::success(data: ['message' => 'La demande a été prise en compte.']);
    }
}
