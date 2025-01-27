<?php

declare(strict_types=1);

namespace Heph\Entity\TermsConditions;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Heph\Entity\InfoDescriptionModel\InfoDescriptionModel;
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

    #[ORM\Column(type: 'string', name: 'libelle', nullable: false)]
    private string $libelle;

    public function libelle(): string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): void
    {
        $this->libelle = $libelle;
        $this->updatedAt = new DateTimeImmutable();
    }

    #[ORM\Column(type: 'string', name: 'description', nullable: false)]
    private string $description;

    public function description(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
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
        string $libelle,
        string $description,
    ) {
        $this->id = Uuid::v7();
        $this->libelle = $libelle;
        $this->description = $description;
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = $this->createdAt;
    }
}
