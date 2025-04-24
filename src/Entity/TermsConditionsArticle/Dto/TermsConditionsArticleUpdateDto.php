<?php

declare(strict_types=1);

namespace Heph\Entity\TermsConditionsArticle\Dto;

use Symfony\Component\Validator\Constraints as Assert;

readonly class TermsConditionsArticleUpdateDto
{
    public function __construct(
        #[Assert\NotBlank(message: "Le title est requis.")]
        public string $title,
        #[Assert\NotBlank(message: "L'article est requis.")]
        public string $article,
    ) {}

    public static function new(string $title, string $article): self
    {
        return new self(
            title: $title,
            article: $article
        );
    }
}
