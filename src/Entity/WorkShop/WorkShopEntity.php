<?php

declare(strict_types=1);

namespace Heph\Entity\WorkShop;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Heph\Entity\InfoDescriptionModel\InfoDescriptionModelEntity;
use Heph\Entity\WorkShop\Type\WorkShopId;
use Heph\Repository\WorkShop\WorkShopEntityRepository;

#[ORM\Entity(repositoryClass: WorkShopEntityRepository::class)]
class WorkShopEntity
{
    #[ORM\Id]
    #[ORM\Column(type: 'app_uid', name: 'id', unique: true)]
    private WorkShopId $id;

    public function id(): WorkShopId
    {
        return $this->id;
    }

    #[ORM\OneToOne(targetEntity: InfoDescriptionModelEntity::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: 'info_description_model', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private InfoDescriptionModelEntity $infoDescriptionModel;

    public function infoDescriptionModel(): InfoDescriptionModelEntity
    {
        return $this->infoDescriptionModel;
    }

    public function setInfoDescriptionModel(InfoDescriptionModelEntity $infoDescriptionModel): void
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
        InfoDescriptionModelEntity $infoDescriptionModel,
    ) {
        $this->id = WorkShopId::create();
        $this->infoDescriptionModel = $infoDescriptionModel;
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = $this->createdAt;
    }
}
