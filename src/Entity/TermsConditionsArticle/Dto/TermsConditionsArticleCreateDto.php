<?php

declare(strict_types=1);

namespace Heph\Entity\TermsConditionsArticle\Dto;

use DateTimeImmutable;
use Heph\Entity\Shared\ValueObject\ArticleValueObject;
use Heph\Entity\Shared\ValueObject\TitleValueObject;
use Heph\Entity\TermsConditions\Dto\TermsConditionsCreateDto;
use Symfony\Component\Uid\Uuid;

class TermsConditionsArticleCreateDto
{
    public function __construct(
        private Uuid $id,
        private TermsConditionsCreateDto $termsConditions,
        private TitleValueObject $title,
        private ArticleValueObject $article,
        private DateTimeImmutable $createdAt,
        private DateTimeImmutable $updatedAt,
    ) {}

    public static function new(
        TermsConditionsCreateDto $termsConditions,
        string $title,
        string $article,
    ): self {
        return new self(
            id: Uuid::v7(),
            termsConditions: $termsConditions,
            title: TitleValueObject::fromValue($title),
            article: ArticleValueObject::fromValue($article),
            createdAt: new DateTimeImmutable(),
            updatedAt: new DateTimeImmutable()
        );
    }

    public function id(): Uuid
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

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
