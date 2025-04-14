<?php

declare(strict_types=1);

namespace Heph\Tests\Functional\Controller\Api\CarSales;

use Heph\Controller\Api\CarSales\CreateCarSales;
use Heph\Controller\Api\WorkShop\CreateWorkShop;
use Heph\Entity\CarSales\Dto\CarSalesCreateDto;
use Heph\Entity\InfoDescriptionModel\Dto\InfoDescriptionModelCreateDto;
use Heph\Entity\Shared\ValueObject\DescriptionValueObject;
use Heph\Entity\Shared\ValueObject\LibelleValueObject;
use Heph\Entity\WorkShop\Dto\WorkShopCreateDto;
use Heph\Infrastructure\ApiResponse\ApiResponse;
use Heph\Infrastructure\ApiResponse\ApiResponseFactory;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseData;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseLink;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseMessage;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseMeta;
use Heph\Infrastructure\ApiResponse\Exception\Custom\AbstractApiResponseException;
use Heph\Infrastructure\ApiResponse\Exception\Custom\CarSales\CarSalesInvalidArgumentException;
use Heph\Infrastructure\ApiResponse\Exception\Error\Error;
use Heph\Infrastructure\ApiResponse\Exception\Error\ListError;
use Heph\Infrastructure\ApiResponse\Exception\Event\ApiResponseExceptionListener;
use Heph\Infrastructure\Serializer\HephSerializer;
use Heph\Infrastructure\Serializer\Normalizer\ValueObjectNormalizer;
use Heph\Message\Command\CarSales\CreateCarSalesCommand;
use Heph\Message\Command\WorkShop\CreateWorkShopCommand;
use Heph\Tests\Functional\HephFunctionalTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

#[
    CoversClass(CreateCarSalesCommand::class),
    CoversClass(HephSerializer::class),
    CoversClass(CreateCarSales::class),
    CoversClass(ApiResponse::class),
    CoversClass(ApiResponseFactory::class),
    CoversClass(ApiResponseData::class),
    CoversClass(ApiResponseLink::class),
    CoversClass(ApiResponseMessage::class),
    CoversClass(ApiResponseMeta::class),
    CoversClass(ListError::class),
    CoversClass(ValueObjectNormalizer::class),
    CoversClass(CarSalesCreateDto::class),
    CoversClass(AbstractApiResponseException::class),
    CoversClass(CarSalesInvalidArgumentException::class),
    CoversClass(Error::class),
    CoversClass(ApiResponseExceptionListener::class),
    CoversClass(InfoDescriptionModelCreateDto::class),
    CoversClass(DescriptionValueObject::class),
    CoversClass(LibelleValueObject::class),
    CoversClass(CreateWorkShop::class),
    CoversClass(WorkShopCreateDto::class),
    CoversClass(CreateWorkShopCommand::class),
]
class CreateCarSalesTest extends HephFunctionalTestCase
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
            'info_description_model' => [
                'libelle' => 'ceci est un libelle test',
                'description' => 'ceci est une description test',
            ],
        ]);

        $this->client->request('POST', '/api/car-sales', [], [], [], $payload);

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
