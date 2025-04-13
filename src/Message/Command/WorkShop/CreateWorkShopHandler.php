<?php

declare(strict_types=1);

namespace Heph\Message\Command\WorkShop;

use Doctrine\ORM\EntityManagerInterface;
use Heph\Entity\InfoDescriptionModel\InfoDescriptionModel;
use Heph\Entity\Shared\ValueObject\DescriptionValueObject;
use Heph\Entity\Shared\ValueObject\LibelleValueObject;
use Heph\Entity\WorkShop\Dto\WorkShopDto;
use Heph\Entity\WorkShop\WorkShop;
use Heph\Infrastructure\ApiResponse\Exception\Custom\Mercure\MercureInvalidArgumentException;
use Heph\Infrastructure\Mercure\MercurePublish;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'command.bus')]
readonly class CreateWorkShopHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private MercurePublish $mercurePublish,
    ) {}

    /**
     * @throws MercureInvalidArgumentException
     */
    public function __invoke(CreateWorkShopCommand $command): void
    {
        $infoDescriptionModel = new InfoDescriptionModel(
            libelle: LibelleValueObject::fromValue($command->workShopCreateDto->infoDescriptionModel->libelle()),
            description: DescriptionValueObject::fromValue($command->workShopCreateDto->infoDescriptionModel->description()),
        );
        $this->entityManager->persist($infoDescriptionModel);

        $workShop = new WorkShop(
            infoDescriptionModel: $infoDescriptionModel,
        );

        $this->entityManager->persist($workShop);
        $this->entityManager->flush();

        $workShopDto = WorkShopDto::fromArray($workShop);

        $this->mercurePublish->publish(
            topic: '/lm-quatre-created',
            data: $workShopDto->toArray()
        );
    }
}
