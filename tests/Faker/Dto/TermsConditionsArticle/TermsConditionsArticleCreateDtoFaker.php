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
            id: '1234',
            termsConditions: TermsConditionsCreateDtoFaker::new(),
            title: 'titre test',
            article: 'article test',
            createdAt: new \DateTimeImmutable('2000-03-31 10:00:00'),
            updatedAt: new \DateTimeImmutable('2000-03-31 12:00:00')
        );
    }
}
