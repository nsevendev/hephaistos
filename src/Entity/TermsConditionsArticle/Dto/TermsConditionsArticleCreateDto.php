<?php

declare(strict_types=1);

namespace Heph\Entity\TermsConditionsArticle\Dto;

use Heph\Entity\TermsConditions\Dto\TermsConditionsCreateDto;
use Symfony\Component\Validator\Constraints as Assert;

readonly class TermsConditionsArticleCreateDto
{
    public function __construct(
        #[Assert\Uuid]
        private string $id,
        #[Assert\Valid]
        private TermsConditionsCreateDto $termsConditions,
        #[Assert\NotBlank]
        private string $title,
        #[Assert\NotBlank]
        private string $article,
        #[Assert\NotBlank]
        private \DateTimeImmutable $createdAt,
        #[Assert\NotBlank]
        private \DateTimeImmutable $updatedAt,
    ) {}

    public static function new(
        string $id,
        TermsConditionsCreateDto $termsConditions,
        string $title,
        string $article,
        \DateTimeImmutable $createdAt,
        \DateTimeImmutable $updatedAt,
    ): self {
        return new self(
            id: $id,
            termsConditions: $termsConditions,
            title: $title,
            article: $article,
            createdAt: $createdAt,
            updatedAt: $updatedAt,
        );
    }

    public function id(): string
    {
        return $this->id;
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

    public function createdAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
