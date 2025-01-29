<?php

declare(strict_types=1);

namespace Heph\Entity\TermsConditionsArticle;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Heph\Entity\TermsConditions\TermsConditions;
use Heph\Entity\Shared\ValueObject\TitleValueObject;
use Heph\Entity\Shared\ValueObject\ArticleValueObject;
use Heph\Repository\TermsConditionsArticle\TermsConditionsArticleRepository;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: TermsConditionsArticleRepository::class)]
class TermsConditionsArticle
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private Uuid $id;

    public function id(): Uuid
    {
        return $this->id;
    }

    #[ORM\OneToOne(targetEntity: TermsConditions::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: 'terms_conditions', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private TermsConditions $termsConditions;

    public function TermsConditions(): TermsConditions
    {
        return $this->termsConditions;
    }

    public function setTermsConditions(TermsConditions $termsConditions): void
    {
        $this->termsConditions = $termsConditions;
        $this->updatedAt = new DateTimeImmutable();
    }

    #[ORM\Column(type: 'string', name: 'title', nullable: false)]
    private string $title;

    public function title(): string
    {
        return $this->title;
    }

    public function setTitle(TitleValueObject $title): void
    {
        $this->title = $title;
        $this->updatedAt = new DateTimeImmutable();
    }

    #[ORM\Column(type: 'string', name: 'article', nullable: false)]
    private string $article;

    public function article(): string
    {
        return $this->article;
    }

    public function setArticle(ArticleValueObject $article): void
    {
        $this->article = $article;
        $this->updatedAt = new DateTimeImmutable();
    }

    #[ORM\Column(type: 'datetime_immutable', name: 'created_at', nullable: false)]
    private DateTimeImmutable $createdAt;

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    #[ORM\Column(type: 'datetime_immutable', name: 'updated_at', nullable: false)]
    private DateTimeImmutable $updatedAt;

    public function updatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function __construct(
        TermsConditions $termsConditions,
        string $title,
        string $article
    ) {
        $this->id = Uuid::v7();
        $this->termsConditions = $termsConditions;
        $this->title = $title;
        $this->article = $article;
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = $this->createdAt;
    }
}
