<?php

declare(strict_types=1);

namespace Heph\Message\Command\LmQuatre;

use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Heph\Entity\InfoDescriptionModel\InfoDescriptionModel;
use Heph\Entity\LmQuatre\Dto\LmQuatreDto;
use Heph\Entity\LmQuatre\LmQuatre;
use Heph\Entity\LmQuatre\ValueObject\LmQuatreAdresse;
use Heph\Entity\LmQuatre\ValueObject\LmQuatreEmail;
use Heph\Entity\LmQuatre\ValueObject\LmQuatreOwner;
use Heph\Entity\LmQuatre\ValueObject\LmQuatrePhoneNumber;
use Heph\Entity\Shared\ValueObject\DescriptionValueObject;
use Heph\Entity\Shared\ValueObject\LibelleValueObject;
use Heph\Infrastructure\ApiResponse\Exception\Custom\Mercure\MercureInvalidArgumentException;
use Heph\Infrastructure\Mercure\MercurePublish;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'command.bus')]
readonly class CreateLmQuatreHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private MercurePublish $mercurePublish,
    ) {}

    /**
     * @throws MercureInvalidArgumentException
     */
    public function __invoke(CreateLmQuatreCommand $command): void
    {
        $infoDescriptionModel = new InfoDescriptionModel(
            libelle: LibelleValueObject::fromValue($command->lmQuatreCreateDto->infoDescriptionModel->libelle()),
            description: DescriptionValueObject::fromValue($command->lmQuatreCreateDto->infoDescriptionModel->description()),
        );
        $this->entityManager->persist($infoDescriptionModel);

        $lmQuatre = new LmQuatre(
            infoDescriptionModel: $infoDescriptionModel,
            owner: LmQuatreOwner::fromValue($command->lmQuatreCreateDto->owner),
            adresse: LmQuatreAdresse::fromValue($command->lmQuatreCreateDto->adresse),
            email: LmQuatreEmail::fromValue($command->lmQuatreCreateDto->email),
            phoneNumber: LmQuatrePhoneNumber::fromValue($command->lmQuatreCreateDto->phoneNumber),
            companyCreateDate: $command->lmQuatreCreateDto->companyCreateDate
        );
        $this->entityManager->persist($lmQuatre);

        $this->entityManager->flush();

        $lmQuatreDto = LmQuatreDto::fromArray($lmQuatre);

        $this->mercurePublish->publish(
            topic: '/lm-quatre-created',
            data: $lmQuatreDto->toArray()
        );
    }
}
