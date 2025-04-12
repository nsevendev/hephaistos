<?php

declare(strict_types=1);

namespace Heph\Message\Command\EngineRemap;

use Doctrine\ORM\EntityManagerInterface;
use Heph\Entity\Shared\ValueObject\DescriptionValueObject;
use Heph\Entity\Shared\ValueObject\LibelleValueObject;
use Heph\Repository\EngineRemap\EngineRemapRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'command.bus')]
class UpdateEngineRemapHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly EngineRemapRepository $engineRemapRepository,
    ) {}

    public function __invoke(UpdateEngineRemapCommand $command): void
    {
        $engineRemap = $this->engineRemapRepository->find($command->id);
        if ($engineRemap) {
            $info = $engineRemap->infoDescriptionModel();
            $info->setLibelle(LibelleValueObject::fromValue($command->engineRemapUpdateDto->libelle));
            $info->setDescription(DescriptionValueObject::fromValue($command->engineRemapUpdateDto->description));
            $this->entityManager->persist($info);
            $this->entityManager->persist($engineRemap);
            $this->entityManager->flush();
        }
    }
}
