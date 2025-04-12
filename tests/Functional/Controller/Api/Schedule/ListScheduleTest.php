<?php

declare(strict_types=1);

namespace Heph\Tests\Functional\Controller\Api\Schedule;

use Doctrine\DBAL\Exception;
use Heph\Controller\Api\Schedule\ListSchedule;
use Heph\Entity\Schedule\Dto\ScheduleDto;
use Heph\Entity\Schedule\Schedule;
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
use Heph\Infrastructure\ApiResponse\Exception\Custom\Schedule\ScheduleInvalidArgumentException;
use Heph\Infrastructure\ApiResponse\Exception\Error\ListError;
use Heph\Infrastructure\Doctrine\Types\Schedule\ScheduleDayType;
use Heph\Infrastructure\Doctrine\Types\Schedule\ScheduleHoursCloseAmType;
use Heph\Infrastructure\Doctrine\Types\Schedule\ScheduleHoursClosePmType;
use Heph\Infrastructure\Doctrine\Types\Schedule\ScheduleHoursOpenAmType;
use Heph\Infrastructure\Doctrine\Types\Schedule\ScheduleHoursOpenPmType;
use Heph\Infrastructure\Serializer\HephSerializer;
use Heph\Message\Query\Schedule\GetListScheduleHandler;
use Heph\Repository\Schedule\ScheduleRepository;
use Heph\Tests\Faker\Entity\Schedule\ScheduleFaker;
use Heph\Tests\Functional\HephFunctionalTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;

#[
    CoversClass(ListSchedule::class),
    CoversClass(ApiResponse::class),
    CoversClass(ApiResponseFactory::class),
    CoversClass(ApiResponseData::class),
    CoversClass(ApiResponseLink::class),
    CoversClass(ApiResponseMessage::class),
    CoversClass(ApiResponseMeta::class),
    CoversClass(ListError::class),
    CoversClass(ScheduleDto::class),
    CoversClass(HephSerializer::class),
    CoversClass(GetListScheduleHandler::class),
    CoversClass(ScheduleRepository::class),
    CoversClass(Schedule::class),
    CoversClass(ScheduleDay::class),
    CoversClass(ScheduleHoursOpenAm::class),
    CoversClass(ScheduleHoursCloseAm::class),
    CoversClass(ScheduleHoursOpenPm::class),
    CoversClass(ScheduleHoursClosePm::class),
    CoversClass(ScheduleDayType::class),
    CoversClass(ScheduleHoursOpenAmType::class),
    CoversClass(ScheduleHoursCloseAmType::class),
    CoversClass(ScheduleHoursOpenPmType::class),
    CoversClass(ScheduleHoursClosePmType::class),
]
class ListScheduleTest extends HephFunctionalTestCase
{
    private KernelBrowser $client;

    public function setUp(): void
    {
        $this->client = self::createClient();
    }

    public function testInvokeReturnsExpectedResponse(): void
    {
        $this->client->request('GET', '/api/list-schedule');

        $content = $this->client->getResponse()->getContent();

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertJson((string) $content);

        $response = json_decode((string) $content, true);

        self::assertArrayHasKey('data', $response);
    }

    /**
     * @throws Exception
     * @throws ScheduleInvalidArgumentException
     */
    public function testCreateAndRetrieveSchedule(): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->getConnection()->beginTransaction();

        $shedule = ScheduleFaker::new();

        $entityManager->persist($shedule);
        $entityManager->flush();

        $this->client->request('GET', '/api/list-schedule');

        $content = $this->client->getResponse()->getContent();

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertJson((string) $content);

        $response = json_decode((string) $content, true);
        self::assertIsArray($response);
        self::assertArrayHasKey('data', $response);
        self::assertNotEmpty($response['data']);

        $retrievedSchedule = $response['data'][0];
        self::assertSame('Monday', $retrievedSchedule['day']);
        self::assertSame('09:00', $retrievedSchedule['hoursOpenAm']);
        self::assertSame('12:00', $retrievedSchedule['hoursCloseAm']);
        self::assertSame('13:00', $retrievedSchedule['hoursOpenPm']);
        self::assertSame('17:00', $retrievedSchedule['hoursClosePm']);

        $entityManager->rollback();
    }
}
