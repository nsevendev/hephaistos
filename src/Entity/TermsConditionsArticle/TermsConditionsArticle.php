<?php

declare(strict_types=1);

namespace Heph\Entity\TermsConditionsArticle;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Heph\Entity\TermsConditions\TermsConditions;
use Heph\Repository\TermsConditionsArticle\TermsConditionsArticleRepository;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: TermsConditionsArticleRepository::class)]
class TermsConditionsArticle
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private Uuid $id;

    #[ORM\Column(type: 'datetime_immutable', name: 'created_at', nullable: false)]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable', name: 'updated_at', nullable: false)]
    private DateTimeImmutable $updatedAt;

    public function __construct(
        #[ORM\ManyToOne(targetEntity: TermsConditions::class, inversedBy: 'listTermsConditionsArticle', cascade: ['persist'])]
        #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
        private TermsConditions $termsConditions,
        #[ORM\Column(type: 'app_terms_conditions_article_title', name: 'title', nullable: false)]
        private TermsConditionsArticleTitle $title,
        #[ORM\Column(type: 'app_terms_conditions_article_article', name: 'article', nullable: false)]
        private TermsConditionsArticleArticle $article,
    ) {
        $this->id = Uuid::v7();
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = $this->createdAt;
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function termsConditions(): TermsConditions
    {
        return $this->termsConditions;
    }

    public function title(): TermsConditionsArticleTitle
    {
        return $this->title;
    }

    public function article(): TermsConditionsArticleArticle
    {
        return $this->article;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setTermsConditions(TermsConditions $termsConditions): void
    {
        $this->termsConditions = $termsConditions;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function setTitle(TermsConditionsArticleTitle $title): void
    {
        $this->title = $title;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function setArticle(TermsConditionsArticleArticle $article): void
    {
        $this->article = $article;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function setUpdatedAt(DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
