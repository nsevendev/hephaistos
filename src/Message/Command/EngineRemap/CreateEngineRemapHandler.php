<?php

declare(strict_types=1);

namespace Heph\Message\Command\EngineRemap;

use Doctrine\ORM\EntityManagerInterface;
use Heph\Entity\EngineRemap\Dto\EngineRemapDto;
use Heph\Entity\EngineRemap\EngineRemap;
use Heph\Entity\InfoDescriptionModel\InfoDescriptionModel;
use Heph\Infrastructure\ApiResponse\Exception\Custom\Mercure\MercureInvalidArgumentException;
use Heph\Infrastructure\Mercure\MercurePublish;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'command.bus')]
readonly class CreateEngineRemapHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private MercurePublish $mercurePublish,
    ) {}

    /**
     * @throws MercureInvalidArgumentException
     */
    public function __invoke(CreateEngineRemapCommand $command): void
    {
        $infoDescriptionModel = new InfoDescriptionModel(
            libelle: $command->engineRemapCreateDto->infoDescriptionModel()->libelle()->value(),
            description: $command->engineRemapCreateDto->infoDescriptionModel()->description()->value()
        );
        $this->entityManager->persist($infoDescriptionModel);

        $engineRemap = new EngineRemap(infoDescriptionModel: $infoDescriptionModel);
        $this->entityManager->persist($engineRemap);

        $this->entityManager->flush();

        $engineRemapDto = EngineRemapDto::fromArray($engineRemap);

        $this->mercurePublish->publish(
            topic: '/engine-remap-created',
            data: $engineRemapDto->toArray()
        );
    }
}
