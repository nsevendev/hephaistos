<?php

declare(strict_types=1);

namespace Heph\Entity\TermsConditionsArticle\ValueObject;

use Heph\Infrastructure\ApiResponse\Exception\Custom\TermsConditionsArticle\TermsConditionsArticleInvalidArgumentException;
use Heph\Infrastructure\ApiResponse\Exception\Error\Error;
use JsonSerializable;
use Stringable;

readonly class TermsConditionsArticleArticle implements Stringable, JsonSerializable
{
    public function __construct(private string $value) {}

    /**
     * @throws TermsConditionsArticleInvalidArgumentException
     */
    public static function fromValue(string $value): self
    {
        $valueFormated = trim($value);

        if ('' === $valueFormated) {
            throw new TermsConditionsArticleInvalidArgumentException(getMessage: 'TermsConditionsArticle article ne peux pas etre vide', errors: [Error::create(key: 'TermsConditionsArticleArticle', message: 'TermsConditionsArticle article ne peux pas etre vide')]);
        }

        if (mb_strlen($valueFormated) > 500) {
            throw new TermsConditionsArticleInvalidArgumentException(getMessage: 'TermsConditionsArticle article ne peux pas etre supérieur à 500 caractères', errors: [Error::create(key: 'TermsConditionsArticleArticle', message: 'TermsConditionsArticle article ne peux pas etre supérieur à 500 caractères')]);
        }

        return new self(value: $valueFormated);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function jsonSerialize(): string
    {
        return $this->value;
    }
}
