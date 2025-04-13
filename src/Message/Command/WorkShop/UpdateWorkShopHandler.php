<?php

declare(strict_types=1);

namespace Heph\Message\Command\WorkShop;

use Doctrine\ORM\EntityManagerInterface;
use Heph\Entity\Shared\ValueObject\DescriptionValueObject;
use Heph\Entity\Shared\ValueObject\LibelleValueObject;
use Heph\Repository\WorkShop\WorkShopRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'command.bus')]
class UpdateWorkShopHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly WorkShopRepository $workShopRepository,
    ) {}

    public function __invoke(UpdateWorkShopCommand $command): void
    {
        $workShop = $this->workShopRepository->find($command->id);

        if ($workShop) {
            $dto = $command->workShopUpdateDto;
            $info = $workShop->infoDescriptionModel();
            $info->setLibelle(LibelleValueObject::fromValue($dto->infoDescriptionModel->libelle));
            $info->setDescription(DescriptionValueObject::fromValue($dto->infoDescriptionModel->description));

            $this->entityManager->persist($info);
            $this->entityManager->persist($workShop);
            $this->entityManager->flush();
        }
    }
}
