<?php

declare(strict_types=1);

namespace Heph\Message\Command\TermsConditions;

use Doctrine\ORM\EntityManagerInterface;
use Heph\Entity\Shared\ValueObject\DescriptionValueObject;
use Heph\Entity\Shared\ValueObject\LibelleValueObject;
use Heph\Repository\TermsConditions\TermsConditionsRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'command.bus')]
class UpdateTermsConditionsHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly TermsConditionsRepository $termsConditionsRepository,
    ) {}

    public function __invoke(UpdateTermsConditionsCommand $command): void
    {
        $termsConditions = $this->termsConditionsRepository->find($command->id);

        if ($termsConditions) {
            $dto = $command->termsConditionsUpdateDto;
            $info = $termsConditions->infoDescriptionModel();
            $info->setLibelle(LibelleValueObject::fromValue($dto->infoDescriptionModel->libelle));
            $info->setDescription(DescriptionValueObject::fromValue($dto->infoDescriptionModel->description));

            $this->entityManager->persist($info);
            $this->entityManager->persist($termsConditions);
            $this->entityManager->flush();
        }
    }
}
