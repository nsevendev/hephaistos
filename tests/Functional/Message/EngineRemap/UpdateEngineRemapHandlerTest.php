<?php

declare(strict_types=1);

namespace Heph\Tests\Functional\Message\EngineRemap;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Heph\Entity\EngineRemap\Dto\EngineRemapUpdateDto;
use Heph\Entity\EngineRemap\EngineRemap;
use Heph\Entity\InfoDescriptionModel\InfoDescriptionModel;
use Heph\Entity\Shared\ValueObject\DescriptionValueObject;
use Heph\Entity\Shared\ValueObject\LibelleValueObject;
use Heph\Message\Command\EngineRemap\UpdateEngineRemapCommand;
use Heph\Message\Command\EngineRemap\UpdateEngineRemapHandler;
use Heph\Repository\EngineRemap\EngineRemapRepository;
use Heph\Tests\Faker\Dto\EngineRemap\EngineRemapUpdateDtoFaker;
use Heph\Tests\Faker\Entity\EngineRemap\EngineRemapFaker;
use Heph\Tests\Functional\HephFunctionalTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

#[
    CoversClass(EngineRemapRepository::class),
    CoversClass(EngineRemap::class),
    CoversClass(UpdateEngineRemapCommand::class),
    CoversClass(UpdateEngineRemapHandler::class),
    CoversClass(EngineRemapUpdateDto::class),
    CoversClass(DescriptionValueObject::class),
    CoversClass(LibelleValueObject::class),
    CoversClass(InfoDescriptionModel::class)
]
class UpdateEngineRemapHandlerTest extends HephFunctionalTestCase
{
    use InteractsWithMessenger;

    private EntityManagerInterface $entityManager;
    private EngineRemapRepository $repository;
    private UpdateEngineRemapHandler $handler;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        self::bootKernel();
        $this->entityManager = self::getEntityManager();
        $this->entityManager->getConnection()->beginTransaction();

        $this->repository = $this->entityManager->getRepository(EngineRemap::class);
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
    public function testUpdateEngineRemap(): void
    {
        $engineRemap = EngineRemapFaker::new();
        $this->entityManager->persist($engineRemap);
        $this->entityManager->flush();

        $firstEngineRemap = $this->repository->findFirst();
        self::assertNotNull($firstEngineRemap, 'Entity non trouvée en bdd.');
        self::assertEquals('libelle test', $firstEngineRemap->infoDescriptionModel()->libelle());
        self::assertEquals('description test', $firstEngineRemap->infoDescriptionModel()->description());

        $bus = self::getContainer()->get('messenger.default_bus');
        $dto = EngineRemapUpdateDtoFaker::new();
        $command = new UpdateEngineRemapCommand($dto, (string) $firstEngineRemap->id());
        $bus->dispatch($command);
        $this->flush();

        $this->transport('async')->queue()->assertNotEmpty();
        $this->transport('async')->queue()->assertCount(1);
        $this->transport('async')->process(1);
        $this->transport('async')->queue()->assertCount(0);

        $updatedEngineRemap = $this->repository->findFirst();
        self::assertNotNull($updatedEngineRemap, 'Entity non trouvée en bdd.');

        $updatedInfo = $updatedEngineRemap->infoDescriptionModel();
        self::assertEquals($dto->libelle(), $updatedInfo->libelle(), 'Le libelle ne correspond pas au dto.');
        self::assertEquals($dto->description(), $updatedInfo->description(), 'La description ne correspond pas au dto.');
    }
}
