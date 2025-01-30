<?php

declare(strict_types=1);

namespace Heph\Entity\TermsConditions;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Heph\Entity\InfoDescriptionModel\InfoDescriptionModel;
use Heph\Entity\TermsConditionsArticle\TermsConditionsArticle;
use Heph\Repository\TermsConditions\TermsConditionsRepository;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: TermsConditionsRepository::class)]
class TermsConditions
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private Uuid $id;

    public function id(): Uuid
    {
        return $this->id;
    }

    #[ORM\OneToOne(targetEntity: InfoDescriptionModel::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: 'info_description_model', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private InfoDescriptionModel $infoDescriptionModel;

    public function infoDescriptionModel(): InfoDescriptionModel
    {
        return $this->infoDescriptionModel;
    }

    public function setInfoDescriptionModel(InfoDescriptionModel $infoDescriptionModel): void
    {
        $this->infoDescriptionModel = $infoDescriptionModel;
        $this->updatedAt = new DateTimeImmutable();
    }

    /**
     * @var Collection<int, TermsConditionsArticle> $listTermsConditionsArticle
     */
    #[ORM\OneToMany(mappedBy: 'termsConditions', targetEntity: TermsConditionsArticle::class, cascade: ['persist', 'remove'])]
    private Collection $listTermsConditionsArticle;

    /**
     * @return Collection<int, TermsConditionsArticle>
     */
    public function listTermsConditionsArticle(): Collection
    {
        return $this->listTermsConditionsArticle;
    }

    public function addTermsConditionsArticle(TermsConditionsArticle $article): void
    {
        if (!$this->listTermsConditionsArticle->contains($article)) {
            $this->listTermsConditionsArticle->add($article);
            $article->setTermsConditions($this);
        }
    }

    public function removeTermsConditionsArticle(TermsConditionsArticle $article): void
    {
        if ($this->listTermsConditionsArticle->contains($article)) {
            $this->listTermsConditionsArticle->removeElement($article);
        }
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
        InfoDescriptionModel $infoDescriptionModel,
    ) {
        $this->id = Uuid::v7();
        $this->infoDescriptionModel = $infoDescriptionModel;
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = $this->createdAt();

        /* @var Collection<int, TermsConditionsArticle> */
        $this->listTermsConditionsArticle = new ArrayCollection();
    }
}
