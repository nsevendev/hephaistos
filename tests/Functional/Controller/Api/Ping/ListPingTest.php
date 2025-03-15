<?php

declare(strict_types=1);

namespace Heph\Tests\Functional\Controller\Api\Ping;

use Doctrine\DBAL\Exception;
use Heph\Controller\Api\Ping\ListPing;
use Heph\Entity\Ping\Dto\PingDto;
use Heph\Entity\Ping\Ping;
use Heph\Entity\Ping\ValueObject\PingMessage;
use Heph\Entity\Ping\ValueObject\PingStatus;
use Heph\Infrastructure\ApiResponse\ApiResponse;
use Heph\Infrastructure\ApiResponse\ApiResponseFactory;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseData;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseLink;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseMessage;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseMeta;
use Heph\Infrastructure\ApiResponse\Exception\Custom\Ping\PingInvalidArgumentException;
use Heph\Infrastructure\ApiResponse\Exception\Error\ListError;
use Heph\Infrastructure\Doctrine\Types\Ping\PingMessageType;
use Heph\Infrastructure\Doctrine\Types\Ping\PingStatusType;
use Heph\Infrastructure\Serializer\HephSerializer;
use Heph\Message\Query\Ping\GetListPingHandler;
use Heph\Repository\Ping\PingRepository;
use Heph\Tests\Faker\Entity\Ping\PingFaker;
use Heph\Tests\Functional\HephFunctionalTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;

#[
    CoversClass(ListPing::class),
    CoversClass(ApiResponse::class),
    CoversClass(ApiResponseFactory::class),
    CoversClass(ApiResponseData::class),
    CoversClass(ApiResponseLink::class),
    CoversClass(ApiResponseMessage::class),
    CoversClass(ApiResponseMeta::class),
    CoversClass(ListError::class),
    CoversClass(PingDto::class),
    CoversClass(HephSerializer::class),
    CoversClass(GetListPingHandler::class),
    CoversClass(PingRepository::class),
    CoversClass(Ping::class),
    CoversClass(PingMessage::class),
    CoversClass(PingMessageType::class),
    CoversClass(PingStatus::class),
    CoversClass(PingStatusType::class),
]
class ListPingTest extends HephFunctionalTestCase
{
    private KernelBrowser $client;

    public function setUp(): void
    {
        $this->client = self::createClient();
    }

    public function testInvokeReturnsExpectedResponse(): void
    {
        $this->client->request('GET', '/api/list-ping');

        $content = $this->client->getResponse()->getContent();

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertJson((string) $content);

        $response = json_decode((string) $content, true);

        self::assertArrayHasKey('data', $response);
    }

    /**
     * @throws Exception
     * @throws PingInvalidArgumentException
     */
    public function testCreateAndRetrievePing(): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->getConnection()->beginTransaction();

        $ping = PingFaker::new();

        $entityManager->persist($ping);
        $entityManager->flush();

        $this->client->request('GET', '/api/list-ping');

        $content = $this->client->getResponse()->getContent();

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertJson((string) $content);

        $response = json_decode((string) $content, true);
        self::assertIsArray($response);
        self::assertArrayHasKey('data', $response);
        self::assertNotEmpty($response['data']);

        $retrievedPing = $response['data'][0];
        self::assertSame(200, $retrievedPing['status']);
        self::assertSame('Le ping à réussi', $retrievedPing['message']);

        $entityManager->rollback();
    }
}
