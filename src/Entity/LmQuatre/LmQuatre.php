<?php

declare(strict_types=1);

namespace Heph\Entity\LmQuatre;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Heph\Entity\InfoDescriptionModel\InfoDescriptionModel;
use Heph\Repository\LmQuatre\LmQuatreRepository;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: LmQuatreRepository::class)]
class LmQuatre
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

    #[ORM\Column(type: 'string', name: 'owner', nullable: false)]
    private string $owner;

    public function owner(): string
    {
        return $this->owner;
    }

    public function setOwner(string $owner): void
    {
        $this->owner = $owner;
        $this->updatedAt = new DateTimeImmutable();
    }

    #[ORM\Column(type: 'string', name: 'adresse', nullable: false)]
    private string $adresse;

    public function adresse(): string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): void
    {
        $this->adresse = $adresse;
        $this->updatedAt = new DateTimeImmutable();
    }

    #[ORM\Column(type: 'string', name: 'email', nullable: false)]
    private string $email;

    public function email(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
        $this->updatedAt = new DateTimeImmutable();
    }

    #[ORM\Column(type: 'string', name: 'phone_number', nullable: false)]
    private string $phoneNumber;

    public function phoneNumber(): string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
        $this->updatedAt = new DateTimeImmutable();
    }

    #[ORM\Column(type: 'datetime_immutable', name: 'company_create_date', nullable: false)]
    private DateTimeImmutable $companyCreateDate;

    public function companyCreateDate(): DateTimeImmutable
    {
        return $this->companyCreateDate;
    }

    public function setCompanyCreateDate(DateTimeImmutable $compagnyCreateDate): void
    {
        $this->companyCreateDate = $compagnyCreateDate;
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
        string $owner,
        string $adresse,
        string $email,
        string $phoneNumber,
        DateTimeImmutable $companyCreateDate,
    ) {
        $this->id = Uuid::v7();
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
