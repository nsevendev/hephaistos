<?php

declare(strict_types=1);

namespace Heph\Tests\Functional\Message\TermsConditionsArticle;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Heph\Entity\TermsConditionsArticle\Dto\TermsConditionsArticleCreateDto;
use Heph\Entity\TermsConditionsArticle\Dto\TermsConditionsArticleDto;
use Heph\Entity\TermsConditionsArticle\TermsConditionsArticle;
use Heph\Infrastructure\Mercure\MercurePublish;
use Heph\Message\Command\TermsConditionsArticle\CreateTermsConditionsArticleCommand;
use Heph\Message\Command\TermsConditionsArticle\CreateTermsConditionsArticleHandler;
use Heph\Repository\TermsConditions\TermsConditionsRepository;
use Heph\Repository\TermsConditionsArticle\TermsConditionsArticleRepository;
use Heph\Tests\Faker\Dto\TermsConditionsArticle\TermsConditionsArticleCreateDtoFaker;
use Heph\Tests\Functional\HephFunctionalTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

#[
    CoversClass(TermsConditionsArticleRepository::class),
    CoversClass(TermsConditionsArticle::class),
    CoversClass(CreateTermsConditionsArticleCommand::class),
    CoversClass(TermsConditionsArticleCreateDto::class),
    CoversClass(CreateTermsConditionsArticleHandler::class),
    CoversClass(MercurePublish::class),
    CoversClass(TermsConditionsArticleDto::class),
    CoversClass(TermsConditionsRepository::class),
]
class CreateTermsConditionsArticleHandlerTest extends HephFunctionalTestCase
{
    use InteractsWithMessenger;

    private EntityManagerInterface $entityManager;
    private TermsConditionsArticleRepository $repository;
    private CreateTermsConditionsArticleHandler $handler;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        self::bootKernel();
        $this->entityManager = self::getEntityManager();
        $this->entityManager->getConnection()->beginTransaction();
        $this->repository = $this->entityManager->getRepository(TermsConditionsArticle::class);
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
    public function testHandlerProcessesMessage(): void
    {
        $bus = self::getContainer()->get('messenger.default_bus');
        $dto = TermsConditionsArticleCreateDtoFaker::new('123456789');
        $command = new CreateTermsConditionsArticleCommand($dto);
        $bus->dispatch($command);
        $this->flush();

        $this->transport('async')->queue()->assertNotEmpty();
        $m = $this->transport('async')->queue()->messages();
        self::assertInstanceOf(CreateTermsConditionsArticleCommand::class, $m[0]);
        $this->transport('async')->queue()->assertCount(1);
        $this->transport('async')->process(1);
        $this->transport('async')->queue()->assertCount(0);
    }
}
