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
    CoversClass(UpdateEngineRemapCommand::class)
]
class UpdateEngineRemapTest extends HephFunctionalTestCase
{
    private KernelBrowser $client;

    public function setUp(): void
    {
        $this->client = self::createClient();
    }

    public function testInvokeReturnResponseSucces(): void
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

        // Vérifie que la réponse est correcte
        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        $responseContent = $this->client->getResponse()->getContent();
        $responseData = json_decode($responseContent, true);

        $this->assertArrayHasKey('message', $responseData['data']);
        $this->assertSame('La mise à jour a été prise en compte.', $responseData['data']['message']);
    }
}
