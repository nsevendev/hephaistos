<?php

declare(strict_types=1);

namespace Heph\Entity\TermsConditions\Dto;

use Heph\Entity\InfoDescriptionModel\Dto\InfoDescriptionModelDto;
use Heph\Entity\TermsConditions\TermsConditions;
use Heph\Entity\TermsConditionsArticle\Dto\TermsConditionsArticleDto;

class TermsConditionsDto
{
    /**
     * @param TermsConditionsArticleDto[] $articles
     */
    public function __construct(
        public string $id,
        public InfoDescriptionModelDto $infoDescriptionModel,
        public string $createdAt,
        public string $updatedAt,
        public array $articles = [],
    ) {}

    public static function fromEntity(TermsConditions $data): self
    {
        return new self(
            id: (string) $data->id(),
            infoDescriptionModel: InfoDescriptionModelDto::fromArray($data->infoDescriptionModel()),
            createdAt: $data->createdAt()->format('Y-m-d H:i:s'),
            updatedAt: $data->updatedAt()->format('Y-m-d H:i:s'),
            articles: TermsConditionsArticleDto::toListTermsConditionsArticle(
                $data->listTermsConditionsArticle()->toArray()
            ),
        );
    }

    public static function fromArray(TermsConditions $data): self
    {
        return new self(
            id: (string) $data->id(),
            infoDescriptionModel: InfoDescriptionModelDto::fromEntity($data->infoDescriptionModel()),
            createdAt: $data->createdAt()->format('Y-m-d H:i:s'),
            updatedAt: $data->updatedAt()->format('Y-m-d H:i:s'),
            articles: TermsConditionsArticleDto::toListTermsConditionsArticle(
                $data->listTermsConditionsArticle()->toArray()
            ),
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'infoDescriptionModel' => $this->infoDescriptionModel,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
            'articles' => array_map(
                static fn (TermsConditionsArticleDto $article) => $article->toArray(),
                $this->articles
            ),
        ];
    }

    /**
     * @param TermsConditions[] $data
     *
     * @return TermsConditionsDto[]
     */
    public static function toListTermsConditions(array $data): array
    {
        $listTermsConditions = [];

        foreach ($data as $termsConditions) {
            $listTermsConditions[] = self::fromEntity($termsConditions);
        }

        return $listTermsConditions;
    }
}
