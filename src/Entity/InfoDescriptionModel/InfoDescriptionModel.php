<?php

declare(strict_types=1);

namespace Heph\Entity\InfoDescriptionModel;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Heph\Repository\InfoDescriptionModel\InfoDescriptionModelRepository;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: InfoDescriptionModelRepository::class)]
class InfoDescriptionModel
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private Uuid $id;

    public function id(): Uuid
    {
        return $this->id;
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
