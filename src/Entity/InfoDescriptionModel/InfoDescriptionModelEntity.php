<?php

declare(strict_types=1);

namespace Heph\Entity\InfoDescriptionModel;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Heph\Entity\InfoDescriptionModel\Type\InfoDescriptionModelId;
use Heph\Repository\InfoDescriptionModel\InfoDescriptionModelRepository;
use Heph\Entity\EngineRemap;
use Heph\Entity\CarSales;
use Heph\Entity\LmQuatre;
use Heph\Entity\Workshop;

#[ORM\Entity(repositoryClass: InfoDescriptionModelEntityRepository::class)]
class InfoDescriptionModelEntity
{
    #[ORM\Id]
    #[ORM\Column(type: 'app_uid', unique: true)]
    private InfoDescriptionModelId $id;

    public function id(): InfoDescriptionModelId
    {
        return $this->id;
    }

    #[ORM\Column]
    private string $libelle;

    public function libelle(): string
    {
        return $this->libelle;
    }

    #[ORM\Column]
    private string $description;

    public function description(): string
    {
        return $this->description;
    }

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $createdAt;

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $updatedAt;

    public function updatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    #[ORM\OneToOne(mappedBy: 'infoDescriptionModel', cascade: ['persist', 'remove'])]
    private ?EngineRemap $engineRemap = null;

    #[ORM\OneToOne(mappedBy: 'infoDescriptionModel', cascade: ['persist', 'remove'])]
    private ?CarSales $carSales = null;

    #[ORM\OneToOne(mappedBy: 'infoDescriptionModel', cascade: ['persist', 'remove'])]
    private ?LmQuatre $lmQuatre = null;

    #[ORM\OneToOne(mappedBy: 'infoDescriptionModel', cascade: ['persist', 'remove'])]
    private ?Workshop $workshop = null;

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