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

    public function id(): Uuid
    {
        return $this->id;
    }

    #[ORM\ManyToOne(targetEntity: TermsConditions::class, inversedBy: 'listTermsConditionsArticle', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private TermsConditions $termsConditions;

    public function termsConditions(): TermsConditions
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

    public function setTitle(string $title): void
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

    public function setArticle(string $article): void
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
        string $article,
    ) {
        $this->id = Uuid::v7();
        $this->termsConditions = $termsConditions;
        $this->title = $title;
        $this->article = $article;
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = $this->createdAt;
    }
}
