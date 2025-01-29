<?php

declare(strict_types=1);

namespace Heph\Tests\Functional\Controller\Api\Mercure;

use Heph\Controller\Api\Mercure\SubscribeJwtGenerate;
use Heph\Infrastructure\ApiResponse\ApiResponse;
use Heph\Infrastructure\ApiResponse\ApiResponseFactory;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseData;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseLink;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseMessage;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseMeta;
use Heph\Infrastructure\ApiResponse\Exception\Error\ListError;
use Heph\Infrastructure\Mercure\MercureJwtGenerator;
use Heph\Infrastructure\Serializer\HephSerializer;
use Heph\Tests\Functional\HephFunctionalTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

#[
    CoversClass(SubscribeJwtGenerate::class),
    CoversClass(MercureJwtGenerator::class),
    CoversClass(ApiResponseFactory::class),
    CoversClass(ApiResponse::class),
    CoversClass(ApiResponseMeta::class),
    CoversClass(ApiResponseData::class),
    CoversClass(ApiResponseLink::class),
    CoversClass(ApiResponseMessage::class),
    CoversClass(ListError::class),
    CoversClass(HephSerializer::class)
]
class SubscribeJwtGenerateTest extends HephFunctionalTestCase
{
    private KernelBrowser $client;

    public function setUp(): void
    {
        parent::setUp();
        $this->client = self::createClient();
    }

    public function testSendToken(): void
    {
        $this->client->request('GET', '/api/mercure/jwt/sub');

        $content = $this->client->getResponse()->getContent();

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(200);
        self::assertJson($content);

        $response = json_decode($content, true);

        self::assertArrayHasKey('tokenMercureSubscribe', $response['data']);
        self::assertIsString($response['data']['tokenMercureSubscribe']);
    }
}
