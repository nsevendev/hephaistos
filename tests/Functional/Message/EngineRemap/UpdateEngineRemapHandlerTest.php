<?php

declare(strict_types=1);

namespace Heph\Tests\Functional\Message\EngineRemap;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Heph\Entity\EngineRemap\EngineRemap;
use Heph\Entity\EngineRemap\Dto\EngineRemapUpdateDto;
use Heph\Entity\InfoDescriptionModel\InfoDescriptionModel;
use Heph\Message\Command\EngineRemap\UpdateEngineRemapCommand;
use Heph\Message\Command\EngineRemap\UpdateEngineRemapHandler;
use Heph\Repository\EngineRemap\EngineRemapRepository;
use Heph\Tests\Faker\Dto\EngineRemap\EngineRemapUpdateDtoFaker;
use Heph\Tests\Functional\HephFunctionalTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

#[
    CoversClass(EngineRemapRepository::class),
    CoversClass(EngineRemap::class),
    CoversClass(UpdateEngineRemapCommand::class),
    CoversClass(UpdateEngineRemapHandler::class),
    CoversClass(EngineRemapUpdateDto::class)
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

        $engineRemap = new EngineRemap(new InfoDescriptionModel("libelle avant update", "description avant update"));
        $this->entityManager->persist($engineRemap);
        $this->entityManager->flush();
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
    public function testHandlerProcessesUpdateMessage(): void
    {
        $bus = self::getContainer()->get('messenger.default_bus');
        $dto = EngineRemapUpdateDtoFaker::new();
        $command = new UpdateEngineRemapCommand($dto);
        $bus->dispatch($command);
        $this->flush();
        
        $this->transport('async')->queue()->assertNotEmpty();
        $m = $this->transport('async')->queue()->messages();
        self::assertInstanceOf(UpdateEngineRemapCommand::class, $m[0]);
        $this->transport('async')->queue()->assertCount(1);
        $this->transport('async')->process(1);

        $engineRemap = $this->repository->findFirst();
        $info = $engineRemap->infoDescriptionModel();
        
        if ($dto->libelle() !== null) {
            self::assertEquals($dto->libelle(), $info->libelle());
        }
        if ($dto->description() !== null) {
            self::assertEquals($dto->description(), $info->description());
        }

        $this->transport('async')->queue()->assertCount(0);
    }
}
