<?php

declare(strict_types=1);

namespace Heph\Message\Command\EngineRemap;

use Doctrine\ORM\EntityManagerInterface;
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
            $info->setLibelle($command->engineRemapUpdateDto->libelle()->value());
            $info->setDescription($command->engineRemapUpdateDto->description()->value());

            $this->entityManager->persist($info);
            $this->entityManager->persist($engineRemap);
            $this->entityManager->flush();
        }
    }
}
