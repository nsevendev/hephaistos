<?php

declare(strict_types=1);

namespace Heph\Tests\Functional\Controller\Api\Schedule;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Heph\Controller\Api\Schedule\UpdateSchedule;
use Heph\Entity\InfoDescriptionModel\InfoDescriptionModel;
use Heph\Entity\Schedule\Dto\ScheduleUpdateDto;
use Heph\Entity\Schedule\Schedule;
use Heph\Entity\Schedule\ValueObject\ScheduleDay;
use Heph\Entity\Schedule\ValueObject\ScheduleHoursCloseAm;
use Heph\Entity\Schedule\ValueObject\ScheduleHoursClosePm;
use Heph\Entity\Schedule\ValueObject\ScheduleHoursOpenAm;
use Heph\Entity\Schedule\ValueObject\ScheduleHoursOpenPm;
use Heph\Entity\Shared\ValueObject\DescriptionValueObject;
use Heph\Entity\Shared\ValueObject\LibelleValueObject;
use Heph\Infrastructure\ApiResponse\ApiResponse;
use Heph\Infrastructure\ApiResponse\ApiResponseFactory;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseData;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseLink;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseMessage;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseMeta;
use Heph\Infrastructure\ApiResponse\Exception\Error\ListError;
use Heph\Infrastructure\Doctrine\Types\Schedule\ScheduleDayType;
use Heph\Infrastructure\Doctrine\Types\Schedule\ScheduleHoursCloseAmType;
use Heph\Infrastructure\Doctrine\Types\Schedule\ScheduleHoursClosePmType;
use Heph\Infrastructure\Doctrine\Types\Schedule\ScheduleHoursOpenAmType;
use Heph\Infrastructure\Doctrine\Types\Schedule\ScheduleHoursOpenPmType;
use Heph\Infrastructure\Doctrine\Types\Shared\DescriptionType;
use Heph\Infrastructure\Doctrine\Types\Shared\LibelleType;
use Heph\Infrastructure\Serializer\HephSerializer;
use Heph\Infrastructure\Serializer\Normalizer\ValueObjectNormalizer;
use Heph\Message\Command\Schedule\UpdateScheduleCommand;
use Heph\Repository\Schedule\ScheduleRepository;
use Heph\Tests\Faker\Entity\Schedule\ScheduleFaker;
use Heph\Tests\Functional\HephFunctionalTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

#[
    CoversClass(UpdateSchedule::class),
    CoversClass(Schedule::class),
    CoversClass(UpdateScheduleCommand::class),
    CoversClass(ScheduleUpdateDto::class),
    CoversClass(ApiResponse::class),
    CoversClass(ApiResponseFactory::class),
    CoversClass(ApiResponseData::class),
    CoversClass(ApiResponseLink::class),
    CoversClass(ApiResponseMessage::class),
    CoversClass(ApiResponseMeta::class),
    CoversClass(ListError::class),
    CoversClass(HephSerializer::class),
    CoversClass(UpdateScheduleCommand::class),
    CoversClass(ScheduleRepository::class),
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
    CoversClass(ValueObjectNormalizer::class)
]
class UpdateScheduleTest extends HephFunctionalTestCase
{
    use InteractsWithMessenger;

    private KernelBrowser $client;
    private EntityManagerInterface $entityManager;
    private ScheduleRepository $repository;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->client = $this->createClient();
        $this->entityManager = $this->getEntityManager();
        $this->entityManager->getConnection()->beginTransaction();
        $this->repository = $this->entityManager->getRepository(Schedule::class);
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
        $schedule = ScheduleFaker::new();
        $this->entityManager->persist($schedule);
        $this->entityManager->flush();

        $scheduleToUpdate = $this->repository->findOneBy([]);

        $updatePayload = json_encode([
            'day' => 'Saturday',
            'hours_open_am' => '10:00',
            'hours_close_am' => '11:00',
            'hours_open_pm' => '15:00',
            'hours_close_pm' => '16:00',
        ]);

        $this->client->request('PUT', '/api/schedule/' . (string) $scheduleToUpdate->id(), [], [], [], $updatePayload);
        $responseContent = $this->client->getResponse()->getContent();

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertJson($responseContent);

        $response = json_decode($responseContent, true);
        self::assertArrayHasKey('data', $response);
    }

    public function testInvokeReturnResponseSuccess(): void
    {
        $schedule = ScheduleFaker::new();
        $this->entityManager->persist($schedule);
        $this->entityManager->flush();
        $scheduleToUpdate = $this->repository->findOneBy([]);

        $updatePayload = json_encode([
            'day' => 'Saturday',
            'hours_open_am' => '10:00',
            'hours_close_am' => '11:00',
            'hours_open_pm' => '15:00',
            'hours_close_pm' => '16:00',
        ]);

        $this->client->request('PUT', '/api/schedule/' . (string) $scheduleToUpdate->id(), [], [], [], $updatePayload);
        $responseContent = $this->client->getResponse()->getContent();

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertJson($responseContent);

        $response = json_decode($responseContent, true);
        self::assertArrayHasKey('data', $response);
        self::assertArrayHasKey('message', $response['data']);
        self::assertEquals('La mise à jour a été prise en compte.', $response['data']['message']);
    }
}
