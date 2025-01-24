<?php

declare(strict_types=1);

namespace Heph\Entity\InfoDescriptionModel;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Heph\Entity\InfoDescriptionModel\Type\InfoDescriptionModelId;
use Heph\Repository\InfoDescriptionModel\InfoDescriptionModelEntityRepository;

#[ORM\Entity(repositoryClass: InfoDescriptionModelEntityRepository::class)]
class InfoDescriptionModelEntity
{
    #[ORM\Id]
    #[ORM\Column(type: 'app_uid', name: 'id', nullable: false, unique: true)]
    private InfoDescriptionModelId $id;

    public function id(): InfoDescriptionModelId
    {
        return $this->id;
    }

    #[ORM\Column(name: 'libelle', nullable: false)]
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

    #[ORM\Column(name: 'description', nullable: false)]
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
        $this->id = InfoDescriptionModelId::create();
        $this->libelle = $libelle;
        $this->description = $description;
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = $this->createdAt;
    }
}
