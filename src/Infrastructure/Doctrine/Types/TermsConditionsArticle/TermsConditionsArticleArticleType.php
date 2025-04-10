<?php

declare(strict_types=1);

namespace Heph\Infrastructure\Doctrine\Types\TermsConditionsArticle;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Heph\Entity\TermsConditionsArticle\ValueObject\TermsConditionsArticleArticle;
use Heph\Infrastructure\ApiResponse\Exception\Custom\TermsConditionsArticle\TermsConditionsArticleInvalidArgumentException;
use Heph\Infrastructure\ApiResponse\Exception\Error\Error;

final class TermsConditionsArticleArticleType extends Type
{
    public function getName(): string
    {
        return 'app_terms_conditions_article_article';
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL($column);
    }

    /**
     * @throws TermsConditionsArticleInvalidArgumentException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?TermsConditionsArticleArticle
    {
        if (null === $value) {
            return null;
        }

        if (false === is_string($value)) {
            throw new TermsConditionsArticleInvalidArgumentException(getMessage: 'TermsConditionsArticle article doit être une chaine de caractères', errors: [Error::create(key: 'TermsConditionsArticleArticleType', message: 'TermsConditionsArticle article doit être une chaine de caractères')]);
        }

        return TermsConditionsArticleArticle::fromValue($value);
    }

    /**
     * @throws TermsConditionsArticleInvalidArgumentException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (null === $value) {
            return null;
        }

        if (false === $value instanceof TermsConditionsArticleArticle) {
            throw new TermsConditionsArticleInvalidArgumentException(getMessage: 'La valeur doit etre une instance de TermsConditionsArticleArticle', errors: [Error::create(key: 'TermsConditionsArticleArticleType', message: 'La valeur doit etre une instance de TermsConditionsArticleArticle')]);
        }

        return $value->value();
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
