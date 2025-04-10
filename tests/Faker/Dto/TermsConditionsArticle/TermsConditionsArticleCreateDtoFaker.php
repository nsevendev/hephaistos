<?php

declare(strict_types=1);

namespace Heph\Tests\Faker\Dto\TermsConditionsArticle;

use Heph\Entity\TermsConditionsArticle\Dto\TermsConditionsArticleCreateDto;
use Heph\Tests\Faker\Dto\TermsConditions\TermsConditionsCreateDtoFaker;

class TermsConditionsArticleCreateDtoFaker
{
    public static function new(): TermsConditionsArticleCreateDto
    {
        return new TermsConditionsArticleCreateDto(
            termsConditions: TermsConditionsCreateDtoFaker::new(),
            title: 'titre test',
            article: 'article test'
        );
    }
}
