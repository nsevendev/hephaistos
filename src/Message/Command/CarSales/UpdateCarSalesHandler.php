<?php

declare(strict_types=1);

namespace Heph\Message\Command\CarSales;

use Doctrine\ORM\EntityManagerInterface;
use Heph\Entity\Shared\ValueObject\DescriptionValueObject;
use Heph\Entity\Shared\ValueObject\LibelleValueObject;
use Heph\Repository\CarSales\CarSalesRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'command.bus')]
class UpdateCarSalesHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly CarSalesRepository $carSalesRepository,
    ) {}

    public function __invoke(UpdateCarSalesCommand $command): void
    {
        $carSales = $this->carSalesRepository->find($command->id);

        if ($carSales) {
            $dto = $command->carSalesUpdateDto;
            $info = $carSales->infoDescriptionModel();
            $info->setLibelle(LibelleValueObject::fromValue($dto->infoDescriptionModel->libelle));
            $info->setDescription(DescriptionValueObject::fromValue($dto->infoDescriptionModel->description));

            $this->entityManager->persist($info);
            $this->entityManager->persist($carSales);
            $this->entityManager->flush();
        }
    }
}
