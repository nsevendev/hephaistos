<?php

declare(strict_types=1);

namespace Heph\Tests\Functional\Message\WorkShop;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Heph\Entity\InfoDescriptionModel\Dto\InfoDescriptionModelCreateDto;
use Heph\Entity\InfoDescriptionModel\InfoDescriptionModel;
use Heph\Entity\Shared\ValueObject\DescriptionValueObject;
use Heph\Entity\Shared\ValueObject\LibelleValueObject;
use Heph\Entity\WorkShop\Dto\WorkShopUpdateDto;
use Heph\Entity\WorkShop\WorkShop;
use Heph\Infrastructure\Doctrine\Types\Shared\DescriptionType;
use Heph\Infrastructure\Doctrine\Types\Shared\LibelleType;
use Heph\Message\Command\WorkShop\UpdateWorkShopCommand;
use Heph\Message\Command\WorkShop\UpdateWorkShopHandler;
use Heph\Repository\WorkShop\WorkShopRepository;
use Heph\Tests\Faker\Dto\WorkShop\WorkShopUpdateDtoFaker;
use Heph\Tests\Faker\Entity\WorkShop\WorkShopFaker;
use Heph\Tests\Functional\HephFunctionalTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

#[
    CoversClass(WorkShopRepository::class),
    CoversClass(WorkShop::class),
    CoversClass(UpdateWorkShopCommand::class),
    CoversClass(UpdateWorkShopHandler::class),
    CoversClass(WorkShopUpdateDto::class),
    CoversClass(DescriptionValueObject::class),
    CoversClass(LibelleValueObject::class),
    CoversClass(LibelleType::class),
    CoversClass(DescriptionType::class),
    CoversClass(InfoDescriptionModel::class),
    CoversClass(InfoDescriptionModelCreateDto::class)
]
class UpdateWorkShopHandlerTest extends HephFunctionalTestCase
{
    use InteractsWithMessenger;

    private EntityManagerInterface $entityManager;
    private WorkShopRepository $repository;
    private UpdateWorkShopHandler $handler;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        self::bootKernel();
        $this->entityManager = self::getEntityManager();
        $this->entityManager->getConnection()->beginTransaction();

        $this->repository = $this->entityManager->getRepository(WorkShop::class);
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
    public function testUpdateWorkShop(): void
    {
        $dworkShop = WorkShopFaker::new();
        $this->entityManager->persist($dworkShop);
        $this->entityManager->flush();

        $firstWorkShop = $this->repository->findOneBy([]);
        self::assertNotNull($firstWorkShop, 'Entity non trouvée en bdd.');
        self::assertEquals('libelle test', $firstWorkShop->infoDescriptionModel()->libelle());
        self::assertEquals('description test', $firstWorkShop->infoDescriptionModel()->description());

        $bus = self::getContainer()->get('messenger.default_bus');
        $dto = WorkShopUpdateDtoFaker::new();
        $command = new UpdateWorkShopCommand($dto, (string) $firstWorkShop->id());
        $bus->dispatch($command);
        $this->flush();

        $this->transport('async')->queue()->assertNotEmpty();
        $this->transport('async')->queue()->assertCount(1);
        $this->transport('async')->process(1);
        $this->transport('async')->queue()->assertCount(0);

        $updatedWorkShop = $this->repository->findOneBy([]);
        self::assertNotNull($updatedWorkShop, 'Entity non trouvée en bdd.');
    }
}
