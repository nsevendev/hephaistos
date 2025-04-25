<?php

declare(strict_types=1);

namespace Heph\Tests\Faker\Dto\TermsConditionsArticle;

use Heph\Entity\TermsConditionsArticle\Dto\TermsConditionsArticleCreateDto;

class TermsConditionsArticleCreateDtoFaker
{
    public static function new(string $id): TermsConditionsArticleCreateDto
    {
        return new TermsConditionsArticleCreateDto(
            termsConditionsId: $id,
            title: 'titre test',
            article: 'article test'
        );
    }
}
