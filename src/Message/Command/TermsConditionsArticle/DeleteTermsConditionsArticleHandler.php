<?php

declare(strict_types=1);

namespace Heph\Message\Command\TermsConditionsArticle;

use Heph\Entity\TermsConditionsArticle\Dto\TermsConditionsArticlePublishDeletedDto;
use Heph\Infrastructure\ApiResponse\Exception\Custom\Mercure\MercureInvalidArgumentException;
use Heph\Infrastructure\ApiResponse\Exception\Custom\TermsConditionsArticle\TermsConditionsArticleBadRequestException;
use Heph\Infrastructure\ApiResponse\Exception\Error\Error;
use Heph\Infrastructure\Mercure\MercurePublish;
use Heph\Repository\TermsConditionsArticle\TermsConditionsArticleRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'command.bus')]
class DeleteTermsConditionsArticleHandler
{
    public function __construct(private readonly TermsConditionsArticleRepository $termsConditionsArticleRepository, private readonly MercurePublish $mercurePublish) {}

    /**
     * @return void|null
     *
     * @throws MercureInvalidArgumentException
     * @throws TermsConditionsArticleBadRequestException
     */
    public function __invoke(DeleteTermsConditionsArticleCommand $command)
    {
        $termsConditionsArticle = $this->termsConditionsArticleRepository->find($command->id);

        if (null === $termsConditionsArticle) {
            throw new TermsConditionsArticleBadRequestException(errors: [Error::create('termsConditionsArticle', "Aucun termsConditionsArticle n'a été trouvé")]);
        }

        $termsConditionsArticleDto = TermsConditionsArticlePublishDeletedDto::fromArray($termsConditionsArticle);

        $this->termsConditionsArticleRepository->remove($termsConditionsArticle);

        $this->mercurePublish->publish(
            topic: '/terms-conditions-article-deleted',
            data: $termsConditionsArticleDto->toArray()
        );
    }
}
