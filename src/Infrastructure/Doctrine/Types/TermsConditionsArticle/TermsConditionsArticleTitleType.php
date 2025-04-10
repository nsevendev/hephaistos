<?php

declare(strict_types=1);

namespace Heph\Infrastructure\Doctrine\Types\TermsConditionsArticle;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Heph\Entity\TermsConditionsArticle\ValueObject\TermsConditionsArticleTitle;
use Heph\Infrastructure\ApiResponse\Exception\Custom\TermsConditionsArticle\TermsConditionsArticleInvalidArgumentException;
use Heph\Infrastructure\ApiResponse\Exception\Error\Error;

final class TermsConditionsArticleTitleType extends Type
{
    public function getName(): string
    {
        return 'app_terms_conditions_article_title';
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL($column);
    }

    /**
     * @throws TermsConditionsArticleInvalidArgumentException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?TermsConditionsArticleTitle
    {
        if (null === $value) {
            return null;
        }

        if (false === is_string($value)) {
            throw new TermsConditionsArticleInvalidArgumentException(getMessage: 'TermsConditionsArticle title doit être une chaine de caractères', errors: [Error::create(key: 'TermsConditionsArticleTitleType', message: 'TermsConditionsArticle title doit être une chaine de caractères')]);
        }

        return TermsConditionsArticleTitle::fromValue($value);
    }

    /**
     * @throws TermsConditionsArticleInvalidArgumentException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (null === $value) {
            return null;
        }

        if (false === $value instanceof TermsConditionsArticleTitle) {
            throw new TermsConditionsArticleInvalidArgumentException(getMessage: 'La valeur doit etre une instance de TermsConditionsArticleTitle', errors: [Error::create(key: 'TermsConditionsArticleTitleType', message: 'La valeur doit etre une instance de TermsConditionsArticleTitle')]);
        }

        return $value->value();
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
