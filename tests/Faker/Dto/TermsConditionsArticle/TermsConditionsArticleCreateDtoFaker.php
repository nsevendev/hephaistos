<?php

declare(strict_types=1);

namespace Heph\Tests\Faker\Dto\TermsConditionsArticle;

use DateTimeImmutable;
use Heph\Entity\Shared\ValueObject\ArticleValueObject;
use Heph\Entity\Shared\ValueObject\TitleValueObject;
use Heph\Entity\TermsConditionsArticle\Dto\TermsConditionsArticleCreateDto;
use Heph\Tests\Faker\Dto\TermsConditions\TermsConditionsCreateDtoFaker;
use Symfony\Component\Uid\Uuid;

class TermsConditionsArticleCreateDtoFaker
{
    public static function new(): TermsConditionsArticleCreateDto
    {
        return new TermsConditionsArticleCreateDto(
            id: Uuid::v7(),
            termsConditions: TermsConditionsCreateDtoFaker::new(),
            title: TitleValueObject::fromValue('titre test'),
            article: ArticleValueObject::fromValue('article test'),
            createdAt: new DateTimeImmutable('2000-03-31 10:00:00'),
            updatedAt: new DateTimeImmutable('2000-03-31 12:00:00')
        );
    }
}
