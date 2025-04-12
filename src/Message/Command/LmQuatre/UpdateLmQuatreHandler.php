<?php

declare(strict_types=1);

namespace Heph\Message\Command\LmQuatre;

use Doctrine\ORM\EntityManagerInterface;
use Heph\Entity\LmQuatre\ValueObject\LmQuatreAdresse;
use Heph\Entity\LmQuatre\ValueObject\LmQuatreEmail;
use Heph\Entity\LmQuatre\ValueObject\LmQuatreOwner;
use Heph\Entity\LmQuatre\ValueObject\LmQuatrePhoneNumber;
use Heph\Entity\Shared\ValueObject\DescriptionValueObject;
use Heph\Entity\Shared\ValueObject\LibelleValueObject;
use Heph\Repository\LmQuatre\LmQuatreRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'command.bus')]
class UpdateLmQuatreHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly LmQuatreRepository $lmQuatreRepository,
    ) {}

    public function __invoke(UpdateLmQuatreCommand $command): void
    {
        $lmQuatre = $this->lmQuatreRepository->find($command->id);

        if ($lmQuatre) {
            $dto = $command->lmQuatreUpdateDto;
            $info = $lmQuatre->infoDescriptionModel();
            $info->setLibelle(LibelleValueObject::fromValue($dto->infoDescriptionModel->libelle));
            $info->setDescription(DescriptionValueObject::fromValue($dto->infoDescriptionModel->description));

            $lmQuatre->setOwner(LmQuatreOwner::fromValue($dto->owner));
            $lmQuatre->setAdresse(LmQuatreAdresse::fromValue($dto->adresse));
            $lmQuatre->setEmail(LmQuatreEmail::fromValue($dto->email));
            $lmQuatre->setPhoneNumber(LmQuatrePhoneNumber::fromValue($dto->phoneNumber));
            $lmQuatre->setCompanyCreateDate($dto->companyCreateDate);

            $this->entityManager->persist($info);
            $this->entityManager->persist($lmQuatre);
            $this->entityManager->flush();
        }
    }
}
