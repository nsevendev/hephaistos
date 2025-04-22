<?php

declare(strict_types=1);

namespace Heph\Message\Command\TermsConditions;

use Doctrine\ORM\EntityManagerInterface;
use Heph\Entity\InfoDescriptionModel\InfoDescriptionModel;
use Heph\Entity\Shared\ValueObject\DescriptionValueObject;
use Heph\Entity\Shared\ValueObject\LibelleValueObject;
use Heph\Entity\TermsConditions\Dto\TermsConditionsDto;
use Heph\Entity\TermsConditions\TermsConditions;
use Heph\Infrastructure\ApiResponse\Exception\Custom\Mercure\MercureInvalidArgumentException;
use Heph\Infrastructure\Mercure\MercurePublish;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'command.bus')]
readonly class CreateTermsConditionsHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private MercurePublish $mercurePublish,
    ) {}

    /**
     * @throws MercureInvalidArgumentException
     */
    public function __invoke(CreateTermsConditionsCommand $command): void
    {
        $infoDescriptionModel = new InfoDescriptionModel(
            libelle: LibelleValueObject::fromValue($command->termsConditionsCreateDto->infoDescriptionModel->libelle()),
            description: DescriptionValueObject::fromValue($command->termsConditionsCreateDto->infoDescriptionModel->description()),
        );
        $this->entityManager->persist($infoDescriptionModel);

        $termsConditions = new TermsConditions(
            infoDescriptionModel: $infoDescriptionModel,
        );

        $this->entityManager->persist($termsConditions);
        $this->entityManager->flush();

        $termsConditionsDto = TermsConditionsDto::fromArray($termsConditions);

        $this->mercurePublish->publish(
            topic: '/terms-conditions-created',
            data: $termsConditionsDto->toArray()
        );
    }
}
