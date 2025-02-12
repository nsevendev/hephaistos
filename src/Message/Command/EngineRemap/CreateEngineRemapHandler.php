<?php

declare(strict_types=1);

namespace Heph\Message\Command\EngineRemap;

use Heph\Entity\EngineRemap\Dto\EngineRemapDto;
use Heph\Entity\EngineRemap\EngineRemap;
use Heph\Entity\InfoDescriptionModel\InfoDescriptionModel;
use Heph\Infrastructure\ApiResponse\Exception\Custom\Mercure\MercureInvalidArgumentException;
use Heph\Infrastructure\Mercure\MercurePublish;
use Heph\Repository\EngineRemap\EngineRemapRepository;
use Heph\Repository\InfoDescriptionModel\InfoDescriptionModelRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'command.bus')]
readonly class CreateEngineRemapHandler
{
    public function __construct(
        private EngineRemapRepository $engineRemapRepository,
        private InfoDescriptionModelRepository $infoDescriptionModelRepository,
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

        $this->infoDescriptionModelRepository->save($infoDescriptionModel);

        $engineRemap = new EngineRemap(
            infoDescriptionModel: $infoDescriptionModel
        );

        $this->engineRemapRepository->save($engineRemap);

        $engineRemapDto = EngineRemapDto::fromArray($engineRemap);

        $this->mercurePublish->publish(
            topic: '/engine-remap-created',
            data: $engineRemapDto->toArray()
        );
    }
}
