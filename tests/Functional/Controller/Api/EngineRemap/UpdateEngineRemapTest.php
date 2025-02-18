<?php

declare(strict_types=1);

namespace Heph\Tests\Functional\Controller\Api\EngineRemap;

use Heph\Controller\Api\EngineRemap\UpdateEngineRemap;
use Heph\Entity\EngineRemap\Dto\EngineRemapUpdateDto;
use Heph\Entity\EngineRemap\EngineRemap;
use Heph\Entity\InfoDescriptionModel\InfoDescriptionModel;
use Heph\Entity\Shared\ValueObject\DescriptionValueObject;
use Heph\Entity\Shared\ValueObject\LibelleValueObject;
use Heph\Infrastructure\ApiResponse\ApiResponse;
use Heph\Infrastructure\ApiResponse\ApiResponseFactory;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseData;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseLink;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseMessage;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseMeta;
use Heph\Infrastructure\ApiResponse\Exception\Custom\EngineRemap\EngineRemapInvalidArgumentException;
use Heph\Infrastructure\ApiResponse\Exception\Error\ListError;
use Heph\Infrastructure\Serializer\HephSerializer;
use Heph\Infrastructure\Serializer\Normalizer\ValueObjectNormalizer;
use Heph\Message\Command\EngineRemap\UpdateEngineRemapCommand;
use Heph\Repository\EngineRemap\EngineRemapRepository;
use Heph\Tests\Faker\Entity\EngineRemap\EngineRemapFaker;
use Heph\Tests\Functional\HephFunctionalTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;

#[
    CoversClass(UpdateEngineRemap::class),
    CoversClass(ApiResponse::class),
    CoversClass(ApiResponseFactory::class),
    CoversClass(EngineRemapUpdateDto::class),
    CoversClass(EngineRemapInvalidArgumentException::class),
    CoversClass(InfoDescriptionModel::class),
    CoversClass(LibelleValueObject::class),
    CoversClass(DescriptionValueObject::class),
    CoversClass(ApiResponseData::class),
    CoversClass(ApiResponseLink::class),
    CoversClass(ApiResponseMessage::class),
    CoversClass(ApiResponseMeta::class),
    CoversClass(ListError::class),
    CoversClass(HephSerializer::class),
    CoversClass(ValueObjectNormalizer::class),
    CoversClass(EngineRemap::class),
    CoversClass(EngineRemapRepository::class),
    CoversClass(UpdateEngineRemapCommand::class)
]
class UpdateEngineRemapTest extends HephFunctionalTestCase
{
    private KernelBrowser $client;

    public function setUp(): void
    {
        $this->client = self::createClient();
    }

    public function testInvokeReturnsExpectedResponse(): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->getConnection()->beginTransaction();

        $engineRemap = EngineRemapFaker::new();

        $entityManager->persist($engineRemap);
        $entityManager->flush();

        $updatePayload = json_encode([
            'libelle' => 'libelle update',
            'description' => 'description update',
        ]);
        
        $this->client->request('PUT', '/api/engine-remap', [], [], [], $updatePayload);

        $content = $this->client->getResponse()->getContent();

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertJson($content);

        $response = json_decode($content, true);

        self::assertArrayHasKey('data', $response);
    }

    public function testInvokeReturnResponseSucces(): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->getConnection()->beginTransaction();

        $engineRemap = EngineRemapFaker::new();

        $entityManager->persist($engineRemap);
        $entityManager->flush();

        $retrievedEngineRemap = $entityManager->getRepository(EngineRemap::class)->find($engineRemap->id());
        self::assertNotNull($retrievedEngineRemap, 'L\'EngineRemap n\'a pas été trouvé dans la base de données.');

        $test = $this->client->request('GET', '/api/engine-remap');
        
        $updatePayload = json_encode([
            'libelle' => 'libelle update',
            'description' => 'description update',
        ]);
        
        $this->client->request('PUT', '/api/engine-remap', [], [], [], $updatePayload);

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        $entityManager->rollback();
    }
}
