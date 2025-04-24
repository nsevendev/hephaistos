<?php

declare(strict_types=1);

namespace Heph\Entity\TermsConditionsArticle\Dto;

use Heph\Entity\TermsConditionsArticle\TermsConditionsArticle;

class TermsConditionsArticleDeleteDto
{
    public function __construct(
        public string $id,
        public string $title,
        public string $article,
        public string $createdAt,
        public string $updatedAt,
    ) {}

    public static function fromArray(TermsConditionsArticle $data): self
    {
        return new self(
            id: (string) $data->id(),
            title: $data->title()->value(),
            article: $data->article()->value(),
            createdAt: $data->createdAt()->format('Y-m-d H:i:s'),
            updatedAt: $data->updatedAt()->format('Y-m-d H:i:s'),
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'article' => $this->article,
            'delete' => 'true',
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
        ];
    }
}
