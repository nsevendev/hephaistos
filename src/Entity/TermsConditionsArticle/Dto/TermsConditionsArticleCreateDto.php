<?php

declare(strict_types=1);

namespace Heph\Entity\TermsConditionsArticle\Dto;

use Heph\Entity\Shared\ValueObject\ArticleValueObject;
use Heph\Entity\Shared\ValueObject\TitleValueObject;
use Heph\Entity\TermsConditions\Dto\TermsConditionsCreateDto;

class TermsConditionsArticleCreateDto
{
    public function __construct(
        private TermsConditionsCreateDto $termsConditions,
        private TitleValueObject $title,
        private ArticleValueObject $article,
    ) {}

    public static function new(
        TermsConditionsCreateDto $termsConditions,
        string $title,
        string $article,
    ): self {
        return new self(
            termsConditions: $termsConditions,
            title: TitleValueObject::fromValue($title),
            article: ArticleValueObject::fromValue($article),
        );
    }

    public function termsConditions(): TermsConditionsCreateDto
    {
        return $this->termsConditions;
    }

    public function title(): TitleValueObject
    {
        return $this->title;
    }

    public function article(): ArticleValueObject
    {
        return $this->article;
    }
}
