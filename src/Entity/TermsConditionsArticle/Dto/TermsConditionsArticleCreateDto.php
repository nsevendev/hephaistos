<?php

declare(strict_types=1);

namespace Heph\Entity\TermsConditionsArticle\Dto;

use Heph\Entity\Shared\ValueObject\ArticleValueObject;
use Heph\Entity\Shared\ValueObject\TitleValueObject;
use Heph\Entity\TermsConditions\Dto\TermsConditionsCreateDto;

class TermsConditionsArticleCreateDto
{
    public function __construct(
        private string $id,
        private TermsConditionsCreateDto $termsConditions,
        private TitleValueObject $title,
        private ArticleValueObject $article,
        private \DateTimeImmutable $createdAt,
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
            title: TitleValueObject::fromValue($title),
            article: ArticleValueObject::fromValue($article),
            createdAt: $createdAt,
            updatedAt: $updatedAt
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

    public function title(): TitleValueObject
    {
        return $this->title;
    }

    public function article(): ArticleValueObject
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
