<?php

declare(strict_types=1);

namespace Heph\Message\Query\TermsConditions;

use Heph\Entity\TermsConditions\Dto\TermsConditionsDto;
use Heph\Entity\TermsConditions\TermsConditions;
use Heph\Repository\TermsConditions\TermsConditionsRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetListTermsConditionsHandler
{
    public function __construct(private TermsConditionsRepository $termsConditionsRepository) {}

    /**
     * @return TermsConditionsDto[]
     */
    public function __invoke(GetListTermsConditionsQuery $query): array
    {
        /** @var TermsConditions[] $listTermsConditions */
        $listTermsConditions = $this->termsConditionsRepository->findAllWithArticles();

        return TermsConditionsDto::toListTermsConditions($listTermsConditions);
    }
}
