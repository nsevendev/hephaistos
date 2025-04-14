<?php

declare(strict_types=1);

namespace Heph\Message\Command\CarSales;

use Doctrine\ORM\EntityManagerInterface;
use Heph\Entity\CarSales\CarSales;
use Heph\Entity\CarSales\Dto\CarSalesDto;
use Heph\Entity\InfoDescriptionModel\InfoDescriptionModel;
use Heph\Entity\Shared\ValueObject\DescriptionValueObject;
use Heph\Entity\Shared\ValueObject\LibelleValueObject;
use Heph\Infrastructure\ApiResponse\Exception\Custom\Mercure\MercureInvalidArgumentException;
use Heph\Infrastructure\Mercure\MercurePublish;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'command.bus')]
readonly class CreateCarSalesHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private MercurePublish $mercurePublish,
    ) {}

    /**
     * @throws MercureInvalidArgumentException
     */
    public function __invoke(CreateCarSalesCommand $command): void
    {
        $infoDescriptionModel = new InfoDescriptionModel(
            libelle: LibelleValueObject::fromValue($command->carSalesCreateDto->infoDescriptionModel->libelle()),
            description: DescriptionValueObject::fromValue($command->carSalesCreateDto->infoDescriptionModel->description()),
        );
        $this->entityManager->persist($infoDescriptionModel);

        $carSales = new CarSales(
            infoDescriptionModel: $infoDescriptionModel,
        );

        $this->entityManager->persist($carSales);
        $this->entityManager->flush();

        $carSalesDto = CarSalesDto::fromArray($carSales);

        $this->mercurePublish->publish(
            topic: '/car-sales-created',
            data: $carSalesDto->toArray()
        );
    }
}
