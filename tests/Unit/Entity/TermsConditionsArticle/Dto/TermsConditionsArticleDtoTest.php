<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Entity\TermsConditionsArticle\Dto;

use Heph\Entity\InfoDescriptionModel\Dto\InfoDescriptionModelDto;
use Heph\Entity\TermsConditions\Dto\TermsConditionsDto;
use Heph\Entity\TermsConditions\TermsConditions;
use Heph\Entity\TermsConditionsArticle\Dto\TermsConditionsArticleDto;
use Heph\Entity\TermsConditionsArticle\TermsConditionsArticle;
use Heph\Entity\TermsConditionsArticle\ValueObject\TermsConditionsArticleArticle;
use Heph\Entity\TermsConditionsArticle\ValueObject\TermsConditionsArticleTitle;
use Heph\Tests\Faker\Dto\TermsConditionsArticle\TermsConditionsArticleDtoFaker;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(TermsConditionsArticleDto::class), CoversClass(InfoDescriptionModelDto::class), CoversClass(TermsConditionsDto::class), CoversClass(TermsConditionsArticleArticle::class), CoversClass(TermsConditionsArticleTitle::class)]
class TermsConditionsArticleDtoTest extends HephUnitTestCase
{
    public function testTermsConditionsArticleDto(): void
    {
        $dto = TermsConditionsArticleDtoFaker::new();

        self::assertNotNull($dto);
        self::assertInstanceOf(TermsConditionsArticleDto::class, $dto);

        self::assertNotNull($dto->id);
        self::assertSame('Titre test', $dto->title);
        self::assertSame('Article de test', $dto->article);
        self::assertSame('2000-03-31 10:00:00', $dto->createdAt);
        self::assertSame('2000-03-31 12:00:00', $dto->updatedAt);
    }

    public function testToListTermsConditionsArticle(): void
    {
        $termsConditionsArticleMock1 = $this->createMock(TermsConditionsArticle::class);
        $termsConditionsArticleMock1->method('title')->willReturn(new TermsConditionsArticleTitle('Titre test 1'));
        $termsConditionsArticleMock1->method('article')->willReturn(new TermsConditionsArticleArticle('Article de test 1'));
        $termsConditionsArticleMock1->method('createdAt')->willReturn(new \DateTimeImmutable('2000-03-31 10:00:00'));
        $termsConditionsArticleMock1->method('updatedAt')->willReturn(new \DateTimeImmutable('2000-03-31 12:00:00'));

        $termsConditionsArticleMock2 = $this->createMock(TermsConditionsArticle::class);
        $termsConditionsArticleMock2->method('title')->willReturn(new TermsConditionsArticleTitle('Titre test 2'));
        $termsConditionsArticleMock2->method('article')->willReturn(new TermsConditionsArticleArticle('Article de test 2'));
        $termsConditionsArticleMock2->method('createdAt')->willReturn(new \DateTimeImmutable('2001-03-31 10:00:00'));
        $termsConditionsArticleMock2->method('updatedAt')->willReturn(new \DateTimeImmutable('2001-03-31 12:00:00'));

        $entities = [$termsConditionsArticleMock1, $termsConditionsArticleMock2];

        $dtos = TermsConditionsArticleDto::toListTermsConditionsArticle($entities);

        self::assertCount(2, $dtos);

        self::assertNotNull($dtos[0]->id);
        self::assertSame('Titre test 1', $dtos[0]->title);
        self::assertSame('Article de test 1', $dtos[0]->article);
        self::assertSame('2000-03-31 10:00:00', $dtos[0]->createdAt);
        self::assertSame('2000-03-31 12:00:00', $dtos[0]->updatedAt);

        self::assertNotNull($dtos[1]->id);
        self::assertSame('Titre test 2', $dtos[1]->title);
        self::assertSame('Article de test 2', $dtos[1]->article);
        self::assertSame('2001-03-31 10:00:00', $dtos[1]->createdAt);
        self::assertSame('2001-03-31 12:00:00', $dtos[1]->updatedAt);
    }

    public function testToArray(): void
    {
        $termsConditionsMock = $this->createMock(TermsConditions::class);
        $termsConditionsDtoMock = $this->createMock(TermsConditionsDto::class);

        $termsConditionsArticleMock = $this->createMock(TermsConditionsArticle::class);
        $termsConditionsArticleMock->method('termsConditions')->willReturn($termsConditionsMock);
        $termsConditionsArticleMock->method('title')->willReturn(
            new TermsConditionsArticleTitle('Titre test')
        );
        $termsConditionsArticleMock->method('article')->willReturn(
            new TermsConditionsArticleArticle('Article test')
        );
        $termsConditionsArticleMock->method('createdAt')->willReturn(new \DateTimeImmutable('2000-03-31 10:00:00'));
        $termsConditionsArticleMock->method('updatedAt')->willReturn(new \DateTimeImmutable('2000-03-31 12:00:00'));

        $dto = new TermsConditionsArticleDto(
            id: '1234',
            termsConditions: $termsConditionsDtoMock,
            title: 'Titre test',
            article: 'Article test',
            createdAt: '2000-03-31 10:00:00',
            updatedAt: '2000-03-31 12:00:00',
        );

        $array = $dto->toArray();

        $this->assertIsArray($array);
        $this->assertSame('1234', $array['id']);
        $this->assertSame($termsConditionsDtoMock, $array['termsConditions']);
        $this->assertSame('Titre test', $array['title']);
        $this->assertSame('Article test', $array['article']);
        $this->assertSame('2000-03-31 10:00:00', $array['createdAt']);
        $this->assertSame('2000-03-31 12:00:00', $array['updatedAt']);
    }
}
