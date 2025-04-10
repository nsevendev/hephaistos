<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Infrastructure\Doctrine;

use Doctrine\DBAL\Platforms\MySQLPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use Heph\Entity\TermsConditionsArticle\ValueObject\TermsConditionsArticleTitle;
use Heph\Infrastructure\ApiResponse\Exception\Custom\AbstractApiResponseException;
use Heph\Infrastructure\ApiResponse\Exception\Custom\TermsConditionsArticle\TermsConditionsArticleInvalidArgumentException;
use Heph\Infrastructure\ApiResponse\Exception\Error\Error;
use Heph\Infrastructure\Doctrine\Types\TermsConditionsArticle\TermsConditionsArticleTitleType;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[
    CoversClass(TermsConditionsArticleTitleType::class),
    CoversClass(TermsConditionsArticleTitle::class),
    CoversClass(AbstractApiResponseException::class),
    CoversClass(TermsConditionsArticleInvalidArgumentException::class),
    CoversClass(Error::class),
]
final class TermsConditionsArticleTitleTypeTest extends HephUnitTestCase
{
    private TermsConditionsArticleTitleType $type;
    private MySQLPlatform $platform;

    protected function setUp(): void
    {
        if (!Type::hasType('app_terms_conditions_article_title')) {
            Type::addType('app_terms_conditions_article_title', TermsConditionsArticleTitleType::class);
        }

        $this->type = Type::getType('app_terms_conditions_article_title');
        $this->platform = new MySQLPlatform();
    }

    public function testGetName(): void
    {
        self::assertSame('app_terms_conditions_article_title', $this->type->getName());
    }

    public function testGetSQLDeclaration(): void
    {
        $column = ['length' => 50];
        $sql = $this->type->getSQLDeclaration($column, $this->platform);
        self::assertSame('VARCHAR(50)', $sql);
    }

    /**
     * @throws TermsConditionsArticleInvalidArgumentException
     * @throws ConversionException
     */
    public function testConvertToPHPValueWithValidString(): void
    {
        $termsConditionsArticleTitle = $this->type->convertToPHPValue('Hello, World!', $this->platform);
        self::assertInstanceOf(TermsConditionsArticleTitle::class, $termsConditionsArticleTitle);
        self::assertSame('Hello, World!', $termsConditionsArticleTitle->value());
    }

    /**
     * @throws TermsConditionsArticleInvalidArgumentException
     * @throws ConversionException
     */
    public function testConvertToPHPValueWithNull(): void
    {
        $termsConditionsArticleTitle = $this->type->convertToPHPValue(null, $this->platform);
        self::assertNull($termsConditionsArticleTitle);
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
    public function testConvertToDatabaseValueWithValidTermsConditionsArticleTitle(): void
    {
        $termsConditionsArticleTitle = TermsConditionsArticleTitle::fromValue('Hello, Database!');
        $dbValue = $this->type->convertToDatabaseValue($termsConditionsArticleTitle, $this->platform);
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
