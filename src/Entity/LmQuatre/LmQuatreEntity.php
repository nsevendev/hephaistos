<?php

declare(strict_types=1);

namespace Heph\Entity\LmQuatre;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Heph\Entity\InfoDescriptionModel\InfoDescriptionModelEntity;
use Heph\Entity\LmQuatre\Type\LmQuatreId;
use Heph\Repository\LmQuatre\LmQuatreEntityRepository;

#[ORM\Entity(repositoryClass: LmQuatreEntityRepository::class)]
class LmQuatreEntity
{
    #[ORM\Id]
    #[ORM\Column(type: 'app_uid', unique: true)]
    private LmQuatreId $id;

    public function id(): LmQuatreId
    {
        return $this->id;
    }

    #[ORM\OneToOne(targetEntity: InfoDescriptionModelEntity::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: 'info_description', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private InfoDescriptionModelEntity $infoDescriptionModel;

    public function infoDescriptionModel(): InfoDescriptionModelEntity
    {
        return $this->infoDescriptionModel;
    }

    public function setInfoDescriptionModel(InfoDescriptionModelEntity $infoDescriptionModel): void
    {
        $this->infoDescriptionModel = $infoDescriptionModel;
    }

    #[ORM\Column]
    private ?string $owner;

    public function owner(): ?string
    {
        return $this->owner;
    }

    #[ORM\Column]
    private ?string $adresse;

    public function adresse(): ?string
    {
        return $this->adresse;
    }

    public function setadresse(?string $adresse): void
    {
        $this->adresse = $adresse;
    }

    #[ORM\Column]
    private ?string $email;

    public function email(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    #[ORM\Column]
    private ?int $phoneNumber;

    public function phoneNumber(): ?int
    {
        return $this->phoneNumber;
    }

    public function setphoneNumber(?int $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    #[ORM\Column(type: 'datetime_immutable')]
    private ?DateTimeImmutable $companyCreateDate;

    public function companyCreateDate(): ?DateTimeImmutable
    {
        return $this->companyCreateDate;
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
        ?string $owner,
        ?string $adresse,
        ?string $email,
        ?int $phoneNumber,
        ?DateTimeImmutable $companyCreateDate,
    ) {
        $this->id = LmQuatreId::create();
        $this->infoDescriptionModel = $infoDescriptionModel;
        $this->owner = $owner;
        $this->adresse = $adresse;
        $this->email = $email;
        $this->phoneNumber = $phoneNumber;
        $this->companyCreateDate = $companyCreateDate;
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = $this->createdAt;
    }
}
