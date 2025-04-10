<?php

declare(strict_types=1);

namespace Heph\Entity\InfoDescriptionModel;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Heph\Entity\Shared\ValueObject\DescriptionValueObject;
use Heph\Entity\Shared\ValueObject\LibelleValueObject;
use Heph\Repository\InfoDescriptionModel\InfoDescriptionModelRepository;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: InfoDescriptionModelRepository::class)]
class InfoDescriptionModel
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private Uuid $id;

    #[ORM\Column(name: 'created_at', type: 'datetime_immutable', nullable: false)]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(name: 'updated_at', type: 'datetime_immutable', nullable: false)]
    private DateTimeImmutable $updatedAt;

    public function __construct(
        #[ORM\Column(name: 'libelle', type: 'app_shared_libelle', length: 75, nullable: false)]
        private LibelleValueObject $libelle,
        #[ORM\Column(name: 'description', type: 'app_shared_description', length: 255, nullable: false)]
        private DescriptionValueObject $description,
    ) {
        $this->id = Uuid::v7();
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = $this->createdAt;
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function libelle(): LibelleValueObject
    {
        return $this->libelle;
    }

    public function setLibelle(LibelleValueObject $libelle): void
    {
        $this->libelle = $libelle;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function description(): DescriptionValueObject
    {
        return $this->description;
    }

    public function setDescription(DescriptionValueObject $description): void
    {
        $this->description = $description;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
