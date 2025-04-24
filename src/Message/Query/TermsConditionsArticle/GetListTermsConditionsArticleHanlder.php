<?php

declare(strict_types=1);

namespace Heph\Message\Query\TermsConditionsArticle;

use Heph\Entity\TermsConditionsArticle\Dto\TermsConditionsArticleDto;
use Heph\Entity\TermsConditionsArticle\TermsConditionsArticle;
use Heph\Repository\TermsConditionsArticle\TermsConditionsArticleRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetListTermsConditionsArticleHandler
{
    public function __construct(private TermsConditionsArticleRepository $termsConditionsRepository) {}

    /**
     * @return TermsConditionsArticleDto[]
     */
    public function __invoke(GetListTermsConditionsArticleQuery $query): array
    {
        /** @var TermsConditionsArticle[] $listTermsConditionsArticle */
        $listTermsConditionsArticle = $this->termsConditionsRepository->findAll();

        return TermsConditionsArticleDto::toListTermsConditionsArticle($listTermsConditionsArticle);
    }
}
