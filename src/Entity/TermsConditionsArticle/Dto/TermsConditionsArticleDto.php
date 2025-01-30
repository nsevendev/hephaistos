<?php

declare(strict_types=1);

namespace Heph\Entity\TermsConditionsArticle\Dto;

use Heph\Entity\TermsConditions\Dto\TermsConditionsDto;
use Heph\Entity\TermsConditionsArticle\TermsConditionsArticle;

class TermsConditionsArticleDto
{
    public function __construct(
        public string $id,
        public TermsConditionsDto $termsConditions,
        public string $title,
        public string $article,
        public string $createdAt,
        public string $updatedAt,
    ) {}

    public static function fromEntity(TermsConditionsArticle $data): self
    {
        return new self(
            id: (string) $data->id(),
            termsConditions: TermsConditionsDto::fromEntity($data->termsConditions()),
            title: $data->title(),
            article: $data->article(),
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

        foreach ($data as $termsConditionsArticle) {
            $listTermsConditionsArticle[] = self::fromEntity($termsConditionsArticle);
        }

        return $listTermsConditionsArticle;
    }
}
