<?php

declare(strict_types=1);

namespace Heph\Message\Command\TermsConditionsArticle;

class DeleteTermsConditionsArticleCommand
{
    public function __construct(
        public string $id,
    ) {}
}
