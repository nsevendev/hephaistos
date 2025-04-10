<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Infrastructure\Doctrine;

use Doctrine\DBAL\Platforms\MySQLPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use Heph\Entity\TermsConditionsArticle\ValueObject\TermsConditionsArticleArticle;
use Heph\Infrastructure\ApiResponse\Exception\Custom\AbstractApiResponseException;
use Heph\Infrastructure\ApiResponse\Exception\Custom\TermsConditionsArticle\TermsConditionsArticleInvalidArgumentException;
use Heph\Infrastructure\ApiResponse\Exception\Error\Error;
use Heph\Infrastructure\Doctrine\Types\TermsConditionsArticle\TermsConditionsArticleArticleType;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[
    CoversClass(TermsConditionsArticleArticleType::class),
    CoversClass(TermsConditionsArticleArticle::class),
    CoversClass(AbstractApiResponseException::class),
    CoversClass(TermsConditionsArticleInvalidArgumentException::class),
    CoversClass(Error::class),
]
final class TermsConditionsArticleArticleTypeTest extends HephUnitTestCase
{
    private TermsConditionsArticleArticleType $type;
    private MySQLPlatform $platform;

    protected function setUp(): void
    {
        if (!Type::hasType('app_terms_conditions_article_article')) {
            Type::addType('app_terms_conditions_article_article', TermsConditionsArticleArticleType::class);
        }

        $this->type = Type::getType('app_terms_conditions_article_article');
        $this->platform = new MySQLPlatform();
    }

    public function testGetName(): void
    {
        self::assertSame('app_terms_conditions_article_article', $this->type->getName());
    }

    public function testGetSQLDeclaration(): void
    {
        $column = ['length' => 500];
        $sql = $this->type->getSQLDeclaration($column, $this->platform);
        self::assertSame('VARCHAR(500)', $sql);
    }

    /**
     * @throws TermsConditionsArticleInvalidArgumentException
     * @throws ConversionException
     */
    public function testConvertToPHPValueWithValidString(): void
    {
        $termsConditionsArticleArticle = $this->type->convertToPHPValue('Hello, World!', $this->platform);
        self::assertInstanceOf(TermsConditionsArticleArticle::class, $termsConditionsArticleArticle);
        self::assertSame('Hello, World!', $termsConditionsArticleArticle->value());
    }

    /**
     * @throws TermsConditionsArticleInvalidArgumentException
     * @throws ConversionException
     */
    public function testConvertToPHPValueWithNull(): void
    {
        $termsConditionsArticleArticle = $this->type->convertToPHPValue(null, $this->platform);
        self::assertNull($termsConditionsArticleArticle);
    }

    /**
     * @throws ConversionException
     */
    public function testConvertToPHPValueWithInvalidType(): void
    {
        $this->expectException(TermsConditionsArticleInvalidArgumentException::class);
        $this->type->convertToPHPValue(123, $this->platform);
    }

    /**
     * @throws TermsConditionsArticleInvalidArgumentException
     * @throws ConversionException
     */
    public function testConvertToDatabaseValueWithValidTermsConditionsArticleArticle(): void
    {
        $termsConditionsArticleArticle = TermsConditionsArticleArticle::fromValue('Hello, Database!');
        $dbValue = $this->type->convertToDatabaseValue($termsConditionsArticleArticle, $this->platform);
        self::assertSame('Hello, Database!', $dbValue);
    }

    /**
     * @throws TermsConditionsArticleInvalidArgumentException
     * @throws ConversionException
     */
    public function testConvertToDatabaseValueWithNull(): void
    {
        $dbValue = $this->type->convertToDatabaseValue(null, $this->platform);
        self::assertNull($dbValue);
    }

    /**
     * @throws ConversionException
     */
    public function testConvertToDatabaseValueWithInvalidType(): void
    {
        $this->expectException(TermsConditionsArticleInvalidArgumentException::class);
        $this->type->convertToDatabaseValue('Invalid Type', $this->platform);
    }

    public function testRequiresSQLCommentHint(): void
    {
        self::assertTrue($this->type->requiresSQLCommentHint($this->platform));
    }
}
