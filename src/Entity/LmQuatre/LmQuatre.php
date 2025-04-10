<?php

declare(strict_types=1);

namespace Heph\Entity\LmQuatre;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Heph\Entity\InfoDescriptionModel\InfoDescriptionModel;
use Heph\Entity\LmQuatre\ValueObject\LmQuatreAdresse;
use Heph\Entity\LmQuatre\ValueObject\LmQuatreEmail;
use Heph\Entity\LmQuatre\ValueObject\LmQuatreOwner;
use Heph\Entity\LmQuatre\ValueObject\LmQuatrePhoneNumber;
use Heph\Repository\LmQuatre\LmQuatreRepository;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: LmQuatreRepository::class)]
class LmQuatre
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private Uuid $id;

    #[ORM\Column(type: 'datetime_immutable', name: 'created_at', nullable: false)]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable', name: 'updated_at', nullable: false)]
    private DateTimeImmutable $updatedAt;

    public function __construct(
        #[ORM\OneToOne(targetEntity: InfoDescriptionModel::class, cascade: ['persist', 'remove'])]
        #[ORM\JoinColumn(name: 'info_description_model', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
        private InfoDescriptionModel $infoDescriptionModel,
        #[ORM\Column(name: 'owner', type: 'app_lm_quatre_owner', length: 50, nullable: false)]
        private LmQuatreOwner $owner,
        #[ORM\Column(name: 'adresse', type: 'app_lm_quatre_adresse', length: 255, nullable: false)]
        private LmQuatreAdresse $adresse,
        #[ORM\Column(name: 'email', type: 'app_lm_quatre_email', length: 50, nullable: false)]
        private LmQuatreEmail $email,
        #[ORM\Column(name: 'phone_number', type: 'app_lm_quatre_phone_number', length: 50, nullable: false)]
        private LmQuatrePhoneNumber $phoneNumber,
        #[ORM\Column(type: 'datetime_immutable', name: 'company_create_date', nullable: false)]
        private DateTimeImmutable $companyCreateDate,
    ) {
        $this->id = Uuid::v7();
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = $this->createdAt;
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function infoDescriptionModel(): InfoDescriptionModel
    {
        return $this->infoDescriptionModel;
    }

    public function owner(): LmQuatreOwner
    {
        return $this->owner;
    }

    public function adresse(): LmQuatreAdresse
    {
        return $this->adresse;
    }

    public function email(): LmQuatreEmail
    {
        return $this->email;
    }

    public function phoneNumber(): LmQuatrePhoneNumber
    {
        return $this->phoneNumber;
    }

    public function companyCreateDate(): DateTimeImmutable
    {
        return $this->companyCreateDate;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setInfoDescriptionModel(InfoDescriptionModel $infoDescriptionModel): void
    {
        $this->infoDescriptionModel = $infoDescriptionModel;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function setOwner(LmQuatreOwner $owner): void
    {
        $this->owner = $owner;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function setAdresse(LmQuatreAdresse $adresse): void
    {
        $this->adresse = $adresse;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function setEmail(LmQuatreEmail $email): void
    {
        $this->email = $email;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function setPhoneNumber(LmQuatrePhoneNumber $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function setCompanyCreateDate(DateTimeImmutable $compagnyCreateDate): void
    {
        $this->companyCreateDate = $compagnyCreateDate;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function setUpdatedAt(DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
