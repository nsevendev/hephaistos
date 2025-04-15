<?php

declare(strict_types=1);

namespace Heph\Tests\Functional\Controller\Api\Users;

use Heph\Controller\Api\Users\CreateUsers;
use Heph\Entity\InfoDescriptionModel\Dto\InfoDescriptionModelCreateDto;
use Heph\Entity\Users\Dto\UsersCreateDto;
use Heph\Entity\Shared\ValueObject\DescriptionValueObject;
use Heph\Entity\Shared\ValueObject\LibelleValueObject;
use Heph\Infrastructure\ApiResponse\ApiResponse;
use Heph\Infrastructure\ApiResponse\ApiResponseFactory;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseData;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseLink;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseMessage;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseMeta;
use Heph\Infrastructure\ApiResponse\Exception\Custom\AbstractApiResponseException;
use Heph\Infrastructure\ApiResponse\Exception\Custom\Users\UsersInvalidArgumentException;
use Heph\Infrastructure\ApiResponse\Exception\Error\Error;
use Heph\Infrastructure\ApiResponse\Exception\Error\ListError;
use Heph\Infrastructure\ApiResponse\Exception\Event\ApiResponseExceptionListener;
use Heph\Infrastructure\Serializer\HephSerializer;
use Heph\Infrastructure\Serializer\Normalizer\ValueObjectNormalizer;
use Heph\Message\Command\Users\CreateUsersCommand;
use Heph\Tests\Functional\HephFunctionalTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

#[
    CoversClass(CreateUsersCommand::class),
    CoversClass(HephSerializer::class),
    CoversClass(CreateUsers::class),
    CoversClass(ApiResponse::class),
    CoversClass(ApiResponseFactory::class),
    CoversClass(ApiResponseData::class),
    CoversClass(ApiResponseLink::class),
    CoversClass(ApiResponseMessage::class),
    CoversClass(ApiResponseMeta::class),
    CoversClass(ListError::class),
    CoversClass(ValueObjectNormalizer::class),
    CoversClass(UsersCreateDto::class),
    CoversClass(AbstractApiResponseException::class),
    CoversClass(UsersInvalidArgumentException::class),
    CoversClass(Error::class),
    CoversClass(ApiResponseExceptionListener::class),
    CoversClass(InfoDescriptionModelCreateDto::class),
    CoversClass(DescriptionValueObject::class),
    CoversClass(LibelleValueObject::class)
]
class CreateUsersTest extends HephFunctionalTestCase
{
    use InteractsWithMessenger;

    private KernelBrowser $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testInvokeReturnResponseSucces(): void
    {
        $payload = json_encode([
            'username' => 'username test',
            'password' => 'password test',
            'role' => 'ROLE_ADMIN',
        ]);

        $this->client->request('POST', '/api/users', [], [], [], $payload);

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        $responseContent = $this->client->getResponse()->getContent();
        $responseData = json_decode($responseContent, true);

        $this->assertArrayHasKey('message', $responseData['data']);
        $this->assertSame('La demande a été prise en compte.', $responseData['data']['message']);

        /* @var InMemoryTransport $transport */
        $this->transport('async')->queue()->assertNotEmpty();
    }
}
