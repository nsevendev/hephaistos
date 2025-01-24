<?php

declare(strict_types=1);

namespace Heph\Tests\Functional\Repository;

use Doctrine\DBAL\Exception;
use Heph\Entity\EngineRemap\EngineRemap;
use Heph\Entity\Shared\Type\Uid;
use Heph\Infrastructure\Doctrine\Type\UidType;
use Heph\Repository\EngineRemap\EngineRemapRepository;
use Heph\Tests\Faker\Entity\EngineRemap\EngineRemapFaker;
use Heph\Tests\Functional\HephFunctionalTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use ReflectionException;

#[
    CoversClass(EngineRemapRepository::class),
    CoversClass(EngineRemap::class),
    CoversClass(Uid::class),
    CoversClass(UidType::class)
]
class EngineRemapRepositoryTest extends HephFunctionalTestCase
{
    private EngineRemapRepository $engineRemapRepository;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->getConnection()->beginTransaction();

        /** @var EngineRemapRepository $repository */
        $repository = self::getContainer()->get(EngineRemapRepository::class);
        $this->engineRemapRepository = $repository;
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
     * @throws ReflectionException
     */
    public function testWeCanPersistAndFindEngineRemap(): void
    {
        $engineRemap = EngineRemapFaker::new();

        $this->persistAndFlush($engineRemap);

        /** @var EngineRemap|null $found */
        $found = $this->engineRemapRepository->find($engineRemap->id());

        self::assertNotNull($found, 'EngineRemap non trouvé en base alors qu’on vient de le créer');
        self::assertInstanceOf(EngineRemap::class, $found);
        self::assertNotNull($found->infoDescriptionModel());
    }

    /**
     * @throws ReflectionException
     */
    public function testWeCanUpdateEngineRemap(): void
    {
        $engineRemap = EngineRemapFaker::new();

        $this->persistAndFlush($engineRemap);

        $infoDescriptionModel = $engineRemap->infoDescriptionModel();
        $infoDescriptionModel->setLibelle('Nouveau libellé');
        $infoDescriptionModel->setDescription('Nouvelle description');

        $this->persistAndFlush($engineRemap);

        /** @var EngineRemap|null $found */
        $found = $this->engineRemapRepository->find($engineRemap->id());

        self::assertNotNull($found, 'EngineRemap non trouvé en base alors qu’on vient de le modifier');
        self::assertSame('Nouveau libellé', $found->infoDescriptionModel()->libelle());
        self::assertSame('Nouvelle description', $found->infoDescriptionModel()->description());
    }
}
