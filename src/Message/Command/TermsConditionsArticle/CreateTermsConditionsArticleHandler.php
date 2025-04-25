<?php

namespace Heph\Message\Command\TermsConditionsArticle;

use Heph\Entity\TermsConditionsArticle\Dto\TermsConditionsArticleDto;
use Heph\Entity\TermsConditionsArticle\TermsConditionsArticle;
use Heph\Entity\TermsConditionsArticle\ValueObject\TermsConditionsArticleArticle;
use Heph\Entity\TermsConditionsArticle\ValueObject\TermsConditionsArticleTitle;
use Heph\Infrastructure\ApiResponse\Exception\Custom\TermsConditionsArticle\TermsConditionsArticleInvalidArgumentException;
use Heph\Infrastructure\Mercure\MercurePublish;
use Heph\Repository\TermsConditions\TermsConditionsRepository;
use Heph\Repository\TermsConditionsArticle\TermsConditionsArticleRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'command.bus')]
readonly class CreateTermsConditionsArticleHandler
{
    public function __construct(
        private TermsConditionsArticleRepository $termsConditionsArticleEntityRepository,
        private TermsConditionsRepository $termsConditionsRepository,
        private MercurePublish $mercurePublish,
    ) {}

    public function __invoke(CreateTermsConditionsArticleCommand $command): void
    {
        $termsConditions = $this->termsConditionsRepository->find($command->termsConditionsArticleCreateDto->termsConditionsId());
        if (!$termsConditions) {
            throw new TermsConditionsArticleInvalidArgumentException('TermsConditions introuvable.');
        }

        $termsConditionsArticle = new TermsConditionsArticle(
            termsConditions: $termsConditions,
            title: TermsConditionsArticleTitle::fromValue($command->termsConditionsArticleCreateDto->title()),
            article: TermsConditionsArticleArticle::fromValue($command->termsConditionsArticleCreateDto->article())
        );

        $termsConditions->addTermsConditionsArticle($termsConditionsArticle);

        $this->termsConditionsArticleEntityRepository->save($termsConditionsArticle);
        $this->termsConditionsRepository->save($termsConditions);

        $termsConditionsArticleDto = TermsConditionsArticleDto::fromArray($termsConditionsArticle);

        $this->mercurePublish->publish(
            topic: '/terms-conditions-article-created',
            data: $termsConditionsArticleDto->toArray()
        );
    }
}
