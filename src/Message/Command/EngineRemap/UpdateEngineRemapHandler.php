<?php

declare(strict_types=1);

namespace Heph\Message\Command\EngineRemap;

use Doctrine\ORM\EntityManagerInterface;
use Heph\Entity\EngineRemap\EngineRemap;
use Heph\Infrastructure\ApiResponse\Exception\Custom\EngineRemap\EngineRemapNotFoundException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'command.bus')]
class UpdateEngineRemapHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {}

    public function __invoke(UpdateEngineRemapCommand $command): void
    {
        $engineRemap = $this->entityManager->getRepository(EngineRemap::class)->findFirst();

        if (!$engineRemap) {
            throw new EngineRemapNotFoundException('Aucun EngineRemap trouvé.');
        }

        $info = $engineRemap->infoDescriptionModel();
        if (null !== $command->engineRemapUpdateDto->libelle()) {
            $info->setLibelle($command->engineRemapUpdateDto->libelle());
        }
        if (null !== $command->engineRemapUpdateDto->description()) {
            $info->setDescription($command->engineRemapUpdateDto->description());
        }

        $this->entityManager->persist($info);
        $this->entityManager->flush();
    }
}
