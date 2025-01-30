<?php

declare(strict_types=1);

namespace Heph\Tests\Faker\Dto\TermsConditionsArticle;

use Heph\Entity\TermsConditionsArticle\Dto\TermsConditionsArticleDto;
use Heph\Tests\Faker\Dto\TermsConditions\TermsConditionsDtoFaker;

class TermsConditionsArticleDtoFaker
{
    public static function new(): TermsConditionsArticleDto
    {
        return new TermsConditionsArticleDto(
            id: '1234',
            termsConditions: TermsConditionsDtoFaker::new(),
            title: 'Titre test',
            article: 'Article de test',
            createdAt: '2000-03-31 10:00:00',
            updatedAt: '2000-03-31 12:00:00'
        );
    }

    /**
     * @param int $count
     *
     * @return TermsConditionsArticleDto[]
     */
    public static function collection(int $count = 3): array
    {
        $dtos = [];

        for ($i = 0; $i < $count; $i++) {
            $dtos[] = new TermsConditionsArticleDto(
                id: (string) ($i + 1),
                termsConditions: TermsConditionsDtoFaker::new(),
                title: 'Titre test ' . ($i + 1),
                article: 'Article de test ' . ($i + 1),
                createdAt: '2000-03-31 10:00:00',
                updatedAt: '2000-03-31 12:00:00'
            );
        }

        return $dtos;
    }
}