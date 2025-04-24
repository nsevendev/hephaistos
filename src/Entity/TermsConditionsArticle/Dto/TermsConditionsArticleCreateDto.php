<?php

declare(strict_types=1);

namespace Heph\Entity\TermsConditionsArticle\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class TermsConditionsArticleCreateDto
{
    public function __construct(
        #[Assert\NotBlank(message: 'Le termsConditionsId est requis.')]
        public string $termsConditionsId,
        #[Assert\NotBlank(message: 'Le title est requis.')]
        #[Assert\Length(max: 50, maxMessage: 'Le title doit contenir au plus {{ limit }} caractères.')]
        public string $title,
        #[Assert\NotBlank(message: 'Le article est requis.')]
        #[Assert\Length(max: 500, maxMessage: 'Le article doit contenir au plus {{ limit }} caractères.')]
        public string $article,
    ) {}

    public static function new(
        string $termsConditionsId,
        string $title,
        string $article,
    ): self {
        return new self(
            termsConditionsId: $termsConditionsId,
            title: $title,
            article: $article,
        );
    }

    public function termsConditionsId(): string
    {
        return $this->termsConditionsId;
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
