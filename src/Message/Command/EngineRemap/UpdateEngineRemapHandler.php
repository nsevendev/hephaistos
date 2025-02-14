<?php

declare(strict_types=1);

namespace Heph\Message\Command\EngineRemap;

use Doctrine\ORM\EntityManagerInterface;
use Heph\Entity\EngineRemap\EngineRemap;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'command.bus')]
class UpdateEngineRemapHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}

    public function __invoke(UpdateEngineRemapCommand $command): void
    {
        $engineRemap = $this->entityManager->getRepository(EngineRemap::class)->findFirst();

        $info = $engineRemap->infoDescriptionModel();
        if ($command->engineRemapUpdateDto->libelle() !== null) {
            $info->setLibelle($command->engineRemapUpdateDto->libelle());
        }
        if ($command->engineRemapUpdateDto->description() !== null) {
            $info->setDescription($command->engineRemapUpdateDto->description());
        }

        $this->entityManager->persist($info);
        $this->entityManager->flush();
    }
}
