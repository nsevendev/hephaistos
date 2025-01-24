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
    #[ORM\Column(type: 'app_uid', unique: true)]
    private WorkShopId $id;

    public function id(): WorkShopId
    {
        return $this->id;
    }

    #[ORM\OneToOne(targetEntity: InfoDescriptionModelEntity::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: 'info_description', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private InfoDescriptionModelEntity $infoDescriptionModel;

    public function getInfoDescriptionModel(): InfoDescriptionModelEntity
    {
        return $this->infoDescriptionModel;
    }

    public function setInfoDescriptionModel(InfoDescriptionModelEntity $infoDescriptionModel): void
    {
        $this->infoDescriptionModel = $infoDescriptionModel;
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

    public function __construct(
        InfoDescriptionModelEntity $infoDescriptionModel,
    ) {
        $this->id = WorkShopId::create();
        $this->infoDescriptionModel = $infoDescriptionModel;
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = $this->createdAt;
    }
}
