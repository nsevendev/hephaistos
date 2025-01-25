<?php

declare(strict_types=1);

namespace Heph\Entity\CarSales;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Heph\Entity\CarSales\Type\CarSalesId;
use Heph\Entity\InfoDescriptionModel\InfoDescriptionModel;
use Heph\Repository\CarSales\CarSalesRepository;

#[ORM\Entity(repositoryClass: CarSalesRepository::class)]
class CarSales
{
    #[ORM\Id]
    #[ORM\Column(type: 'app_uid', name: 'id', nullable: false, unique: true)]
    private CarSalesId $id;

    public function id(): CarSalesId
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
        $this->id = CarSalesId::create();
        $this->infoDescriptionModel = $infoDescriptionModel;
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = $this->createdAt;
    }
}
