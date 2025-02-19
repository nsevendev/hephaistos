<?php

declare(strict_types=1);

namespace Heph\Tests\Functional\Controller\Api\EngineRemap;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Heph\Controller\Api\EngineRemap\UpdateEngineRemap;
use Heph\Entity\EngineRemap\EngineRemap;
use Heph\Entity\InfoDescriptionModel\InfoDescriptionModel;
use Heph\Entity\Shared\ValueObject\DescriptionValueObject;
use Heph\Entity\Shared\ValueObject\LibelleValueObject;
use Heph\Message\Command\EngineRemap\UpdateEngineRemapCommand;
use Heph\Repository\EngineRemap\EngineRemapRepository;
use Heph\Tests\Faker\Entity\EngineRemap\EngineRemapFaker;
use Heph\Tests\Functional\HephFunctionalTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

#[
    CoversClass(UpdateEngineRemap::class),
    CoversClass(EngineRemap::class),
    CoversClass(UpdateEngineRemapCommand::class),
    CoversClass(EngineRemapRepository::class)
]
class UpdateEngineRemapTest extends HephFunctionalTestCase
{
    use InteractsWithMessenger;

    private KernelBrowser $client;
    private EntityManagerInterface $entityManager;
    private EngineRemapRepository $repository;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->client = $this->createClient();
        $this->entityManager = $this->getEntityManager();
        $this->entityManager->getConnection()->beginTransaction();
        $this->repository = $this->entityManager->getRepository(EngineRemap::class);
    }

    /**
     * @throws Exception
     */
    protected function tearDown(): void
    {
        $conn = $this->entityManager->getConnection();
        if ($conn->isTransactionActive()) {
            $conn->rollBack();
        }

        parent::tearDown();
    }

    /**
     * @throws Exception
     */
    public function testDoctrineConfiguration(): void
    {
        $connection = $this->entityManager->getConnection();
        self::assertTrue($connection->isConnected(), 'La connexion à la base de données est inactive');
    }

    public function testInvokeReturnsExpectedResponse(): void
    {
        $engineRemap = EngineRemapFaker::new();
        $this->entityManager->persist($engineRemap);
        $this->entityManager->flush();

        $updatePayload = json_encode([
            'libelle' => 'libelle update',
            'description' => 'description update',
        ]);
        
        $this->client->request('PUT', '/api/engine-remap', [], [], [], $updatePayload);
        $responseContent = $this->client->getResponse()->getContent();

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertJson($responseContent);

        $response = json_decode($responseContent, true);
        self::assertArrayHasKey('data', $response);
    }

    public function testInvokeReturnResponseSuccess(): void
    {
        $updatePayload = json_encode([
            'libelle' => 'libelle mis à jour',
            'description' => 'description mis à jour',
        ]);
    
        $this->client->request('PUT', '/api/engine-remap', [], [], [], $updatePayload);
        $responseContent = $this->client->getResponse()->getContent();

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertJson($responseContent);

        $response = json_decode($responseContent, true);
        self::assertArrayHasKey('data', $response);
        self::assertArrayHasKey('message', $response['data']);
        self::assertEquals('La mise à jour a été prise en compte.', $response['data']['message']);
    
        $updatedEngineRemap = $this->repository->findFirst();
        self::assertNotNull($updatedEngineRemap, 'Entity non trouvée en bdd.');
    
        $infoDescriptionModel = $updatedEngineRemap->infoDescriptionModel();
        self::assertEquals('libelle mis à jour', $infoDescriptionModel->libelle(), 'Le libelle ne correspond pas.');
        self::assertEquals('description mis à jour', $infoDescriptionModel->description(), 'La description ne correspond pas.');
    }
}
