<?php

declare(strict_types=1);

namespace Heph\Message\Command\TermsConditionsArticle;

use Heph\Entity\TermsConditionsArticle\Dto\TermsConditionsArticleCreateDto;

class CreateTermsConditionsArticleCommand
{
    public function __construct(
        public TermsConditionsArticleCreateDto $termsConditionsArticleCreateDto,
    ) {}
}
