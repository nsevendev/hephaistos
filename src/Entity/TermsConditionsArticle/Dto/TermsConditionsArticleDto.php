<?php

declare(strict_types=1);

namespace Heph\Entity\TermsConditionsArticle\Dto;

use Heph\Entity\TermsConditions\TermsConditions;
use Heph\Entity\TermsConditionsArticle\TermsConditionsArticle;

class TermsConditionsArticleDto
{
    public function __construct(
        public string $id,
        public TermsConditions $termsConditions,
        public string $title,
        public string $article,
        public string $createdAt,
        public string $updatedAt,
    ) {}

    public static function fromArray(TermsConditionsArticle $data): self
    {
        return new self(
            id: (string) $data->id(),
            termsConditions: $data->termsConditions(),
            title: $data->title()->value(),
            article: $data->article()->value(),
            createdAt: $data->createdAt()->format('Y-m-d H:i:s'),
            updatedAt: $data->updatedAt()->format('Y-m-d H:i:s'),
        );
    }

    /**
     * @param TermsConditionsArticle[] $data
     *
     * @return TermsConditionsArticleDto[]
     */
    public static function toListTermsConditionsArticle(array $data): array
    {
        $listTermsConditionsArticle = [];

        foreach ($data as $schedule) {
            $listTermsConditionsArticle[] = self::fromArray($schedule);
        }

        return $listTermsConditionsArticle;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'termsConditions' => $this->termsConditions,
            'title' => $this->title,
            'article' => $this->article,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
        ];
    }
}
