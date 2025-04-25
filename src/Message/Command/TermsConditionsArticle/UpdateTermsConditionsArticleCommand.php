<?php

declare(strict_types=1);

namespace Heph\Message\Command\TermsConditionsArticle;

use Heph\Entity\TermsConditionsArticle\Dto\TermsConditionsArticleUpdateDto;

class UpdateTermsConditionsArticleCommand
{
    public function __construct(
        public readonly TermsConditionsArticleUpdateDto $termsConditionsArticleUpdateDto,
        public readonly string $id,
    ) {}
}
