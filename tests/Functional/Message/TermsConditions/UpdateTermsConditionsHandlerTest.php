<?php

declare(strict_types=1);

namespace Heph\Tests\Functional\Message\TermsConditions;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Heph\Entity\InfoDescriptionModel\Dto\InfoDescriptionModelCreateDto;
use Heph\Entity\InfoDescriptionModel\InfoDescriptionModel;
use Heph\Entity\Shared\ValueObject\DescriptionValueObject;
use Heph\Entity\Shared\ValueObject\LibelleValueObject;
use Heph\Entity\TermsConditions\Dto\TermsConditionsUpdateDto;
use Heph\Entity\TermsConditions\TermsConditions;
use Heph\Infrastructure\Doctrine\Types\Shared\DescriptionType;
use Heph\Infrastructure\Doctrine\Types\Shared\LibelleType;
use Heph\Message\Command\TermsConditions\UpdateTermsConditionsCommand;
use Heph\Message\Command\TermsConditions\UpdateTermsConditionsHandler;
use Heph\Repository\TermsConditions\TermsConditionsRepository;
use Heph\Tests\Faker\Dto\TermsConditions\TermsConditionsUpdateDtoFaker;
use Heph\Tests\Faker\Entity\TermsConditions\TermsConditionsFaker;
use Heph\Tests\Functional\HephFunctionalTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

#[
    CoversClass(TermsConditionsRepository::class),
    CoversClass(TermsConditions::class),
    CoversClass(UpdateTermsConditionsCommand::class),
    CoversClass(UpdateTermsConditionsHandler::class),
    CoversClass(TermsConditionsUpdateDto::class),
    CoversClass(DescriptionValueObject::class),
    CoversClass(LibelleValueObject::class),
    CoversClass(LibelleType::class),
    CoversClass(DescriptionType::class),
    CoversClass(InfoDescriptionModel::class),
    CoversClass(InfoDescriptionModelCreateDto::class)
]
class UpdateTermsConditionsHandlerTest extends HephFunctionalTestCase
{
    use InteractsWithMessenger;

    private EntityManagerInterface $entityManager;
    private TermsConditionsRepository $repository;
    private UpdateTermsConditionsHandler $handler;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        self::bootKernel();
        $this->entityManager = self::getEntityManager();
        $this->entityManager->getConnection()->beginTransaction();

        $this->repository = $this->entityManager->getRepository(TermsConditions::class);
    }

    /**
     * @throws Exception
     */
    protected function tearDown(): void
    {
        $conn = $this->getEntityManager()->getConnection();

        if ($conn->isTransactionActive()) {
            $conn->rollBack();
        }
    }

    /**
     * @throws Exception
     */
    public function testDoctrineConfiguration(): void
    {
        $connection = self::getEntityManager()->getConnection();
        self::assertTrue($connection->isConnected(), 'La connexion à la base de données est inactive');
    }

    /**
     * @throws Exception
     */
    public function testUpdateTermsConditions(): void
    {
        $termsConditions = TermsConditionsFaker::new();
        $this->entityManager->persist($termsConditions);
        $this->entityManager->flush();

        $firstTermsConditions = $this->repository->findOneBy([]);
        self::assertNotNull($firstTermsConditions, 'Entity non trouvée en bdd.');
        self::assertEquals('libelle test', $firstTermsConditions->infoDescriptionModel()->libelle());
        self::assertEquals('description test', $firstTermsConditions->infoDescriptionModel()->description());

        $bus = self::getContainer()->get('messenger.default_bus');
        $dto = TermsConditionsUpdateDtoFaker::new();
        $command = new UpdateTermsConditionsCommand($dto, (string) $firstTermsConditions->id());
        $bus->dispatch($command);
        $this->flush();

        $this->transport('async')->queue()->assertNotEmpty();
        $this->transport('async')->queue()->assertCount(1);
        $this->transport('async')->process(1);
        $this->transport('async')->queue()->assertCount(0);

        $updatedTermsConditions = $this->repository->findOneBy([]);
        self::assertNotNull($updatedTermsConditions, 'Entity non trouvée en bdd.');
    }
}
