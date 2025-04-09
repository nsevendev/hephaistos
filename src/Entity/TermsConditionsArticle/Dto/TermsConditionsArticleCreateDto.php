<?php

declare(strict_types=1);

namespace Heph\Entity\TermsConditionsArticle\Dto;

use Heph\Entity\TermsConditions\Dto\TermsConditionsCreateDto;
use Symfony\Component\Validator\Constraints as Assert;

class TermsConditionsArticleCreateDto
{
    public function __construct(
        private TermsConditionsCreateDto $termsConditions,
        #[Assert\NotBlank(message: 'Le title est requis.')]
        #[Assert\Length(max: 50, maxMessage: 'Le title doit contenir au plus {{ limit }} caractères.')]
        private string $title,
        #[Assert\NotBlank(message: 'Le article est requis.')]
        #[Assert\Length(max: 500, maxMessage: 'Le article doit contenir au plus {{ limit }} caractères.')]
        private string $article,
    ) {}

    public static function new(
        TermsConditionsCreateDto $termsConditions,
        string $title,
        string $article,
    ): self {
        return new self(
            termsConditions: $termsConditions,
            title: $title,
            article: $article,
        );
    }

    public function termsConditions(): TermsConditionsCreateDto
    {
        return $this->termsConditions;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function article(): string
    {
        return $this->article;
    }
}
