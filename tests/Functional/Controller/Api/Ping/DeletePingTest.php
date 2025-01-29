<?php

declare(strict_types=1);

namespace Functional\Controller\Api\Ping;

use Heph\Controller\Api\Ping\DeletePing;
use Heph\Controller\Api\Ping\ListPing;
use Heph\Entity\Ping\Dto\PingDto;
use Heph\Entity\Ping\Ping;
use Heph\Infrastructure\ApiResponse\ApiResponse;
use Heph\Infrastructure\ApiResponse\ApiResponseFactory;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseData;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseLink;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseMessage;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseMeta;
use Heph\Infrastructure\ApiResponse\Exception\Error\ListError;
use Heph\Infrastructure\Serializer\HephSerializer;
use Heph\Message\Command\Ping\DeletePingCommand;
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
    CoversClass(DeletePing::class),
    CoversClass(DeletePingCommand::class)
]
class DeletePingTest extends HephFunctionalTestCase
{
    private KernelBrowser $client;

    public function setUp(): void
    {
        $this->client = self::createClient();
    }

    public function testCreateAndDeletePing(): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->getConnection()->beginTransaction();

        $ping = PingFaker::new();

        $entityManager->persist($ping);
        $entityManager->flush();

        $this->client->request('DELETE', '/api/ping/'.$ping->id());

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        $entityManager->rollBack();
    }
}
