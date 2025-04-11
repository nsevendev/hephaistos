<?php

declare(strict_types=1);

namespace Heph\Tests\Functional\Controller\Api\Schedule;

use Heph\Controller\Api\Schedule\CreateSchedule;
use Heph\Entity\Schedule\Dto\ScheduleCreateDto;
use Heph\Entity\Schedule\ValueObject\ScheduleDay;
use Heph\Entity\Schedule\ValueObject\ScheduleHoursCloseAm;
use Heph\Entity\Schedule\ValueObject\ScheduleHoursClosePm;
use Heph\Entity\Schedule\ValueObject\ScheduleHoursOpenAm;
use Heph\Entity\Schedule\ValueObject\ScheduleHoursOpenPm;
use Heph\Infrastructure\ApiResponse\ApiResponse;
use Heph\Infrastructure\ApiResponse\ApiResponseFactory;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseData;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseLink;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseMessage;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseMeta;
use Heph\Infrastructure\ApiResponse\Exception\Custom\AbstractApiResponseException;
use Heph\Infrastructure\ApiResponse\Exception\Custom\Schedule\ScheduleInvalidArgumentException;
use Heph\Infrastructure\ApiResponse\Exception\Error\Error;
use Heph\Infrastructure\ApiResponse\Exception\Error\ListError;
use Heph\Infrastructure\ApiResponse\Exception\Event\ApiResponseExceptionListener;
use Heph\Infrastructure\Serializer\HephSerializer;
use Heph\Infrastructure\Serializer\Normalizer\ValueObjectNormalizer;
use Heph\Message\Command\Schedule\CreateScheduleCommand;
use Heph\Tests\Functional\HephFunctionalTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Transport\InMemory\InMemoryTransport;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

#[
    CoversClass(CreateScheduleCommand::class),
    CoversClass(HephSerializer::class),
    CoversClass(CreateSchedule::class),
    CoversClass(ScheduleDay::class),
    CoversClass(ScheduleHoursOpenAm::class),
    CoversClass(ScheduleHoursCloseAm::class),
    CoversClass(ScheduleHoursOpenPm::class),
    CoversClass(ScheduleHoursClosePm::class),
    CoversClass(ApiResponse::class),
    CoversClass(ApiResponseFactory::class),
    CoversClass(ApiResponseData::class),
    CoversClass(ApiResponseLink::class),
    CoversClass(ApiResponseMessage::class),
    CoversClass(ApiResponseMeta::class),
    CoversClass(ListError::class),
    CoversClass(ValueObjectNormalizer::class),
    CoversClass(ScheduleCreateDto::class),
    CoversClass(AbstractApiResponseException::class),
    CoversClass(ScheduleInvalidArgumentException::class),
    CoversClass(Error::class),
    CoversClass(ApiResponseExceptionListener::class)
]
class CreateScheduleTest extends HephFunctionalTestCase
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
            'day' => 'Monday',
            'hours_open_am' => '08:00',
            'hours_close_am' => '12:00',
            'hours_open_pm' => '13:00',
            'hours_close_pm' => '18:00',
        ]);

        $this->client->request('POST', '/api/schedule', [], [], [], $payload);

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        $responseContent = $this->client->getResponse()->getContent();
        $responseData = json_decode($responseContent, true);

        $this->assertArrayHasKey('message', $responseData['data']);
        $this->assertSame('La demande a été prise en compte.', $responseData['data']['message']);

        /* @var InMemoryTransport $transport */
        $this->transport('async')->queue()->assertNotEmpty();
    }

    public function testInvokeInvalidateArgument(): void
    {
        $payload = json_encode([
            'day' => 'pasunjour',
            'hours_open_am' => '08:00',
            'hours_close_am' => '12:00',
            'hours_open_pm' => '13:00',
            'hours_close_pm' => '18:00',
        ]);

        $this->client->request('POST', '/api/schedule', [], [], [], $payload);

        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);

        $responseContent = $this->client->getResponse()->getContent();
        $responseData = json_decode($responseContent, true);

        $this->assertArrayHasKey('errors', $responseData);
        $this->assertNotEmpty($responseData['errors']);
    }
}
