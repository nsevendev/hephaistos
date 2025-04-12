<?php

declare(strict_types=1);

namespace Heph\Entity\LmQuatre\Dto;

use DateTimeImmutable;
use Heph\Entity\InfoDescriptionModel\Dto\InfoDescriptionModelCreateDto;
use Symfony\Component\Validator\Constraints as Assert;

readonly class LmQuatreUpdateDto
{
    public function __construct(
        #[Assert\NotBlank(message: "L'infoDescriptionModel est requis.")]
        public InfoDescriptionModelCreateDto $infoDescriptionModel,
        #[Assert\NotBlank(message: 'Le owner est requis.')]
        #[Assert\Length(max: 50, maxMessage: 'Le owner doit contenir au plus {{ limit }} caractères.')]
        public string $owner,
        #[Assert\NotBlank(message: 'Le adresse est requis.')]
        #[Assert\Length(max: 255, maxMessage: 'Le adresse doit contenir au plus {{ limit }} caractères.')]
        public string $adresse,
        #[Assert\NotBlank(message: 'Le email est requis.')]
        #[Assert\Length(max: 50, maxMessage: 'Le email doit contenir au plus {{ limit }} caractères.')]
        public string $email,
        #[Assert\NotBlank(message: 'Le phoneNumber est requis.')]
        #[Assert\Length(max: 50, maxMessage: 'Le phoneNumber doit contenir au plus {{ limit }} caractères.')]
        public string $phoneNumber,
        #[Assert\NotBlank(message: "L'companyCreateDate est requis.")]
        public DateTimeImmutable $companyCreateDate,
    ) {}

    public static function new(string $libelle, string $description, string $owner, string $adresse, string $email, string $phoneNumber, DateTimeImmutable $companyCreateDate): self
    {
        return new self(
            infoDescriptionModel: InfoDescriptionModelCreateDto::new(
                $libelle,
                $description
            ),
            owner: $owner,
            adresse: $adresse,
            email: $email,
            phoneNumber: $phoneNumber,
            companyCreateDate: $companyCreateDate
        );
    }
}
