<?php

declare(strict_types=1);

namespace Heph\Tests\Faker\Dto\TermsConditionsArticle;

use Heph\Entity\TermsConditionsArticle\Dto\TermsConditionsArticleUpdateDto;

class TermsConditionsArticleUpdateDtoFaker
{
    public static function new(): TermsConditionsArticleUpdateDto
    {
        return TermsConditionsArticleUpdateDto::new('title update', 'article update');
    }
}
