<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Infrastructure\ApiResponse\Exception\Event;

use Heph\Infrastructure\ApiResponse\ApiResponse;
use Heph\Infrastructure\ApiResponse\ApiResponseFactory;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseData;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseLink;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseMessage;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseMeta;
use Heph\Infrastructure\ApiResponse\Exception\Custom\Ping\PingBadRequestException;
use Heph\Infrastructure\ApiResponse\Exception\Custom\Shared\GenericException;
use Heph\Infrastructure\ApiResponse\Exception\Error\Error;
use Heph\Infrastructure\ApiResponse\Exception\Error\ListError;
use Heph\Infrastructure\ApiResponse\Exception\Event\ApiResponseGenericExceptionListener;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Exception;
use RuntimeException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

#[
    CoversClass(ApiResponseGenericExceptionListener::class),
    CoversClass(GenericException::class),
    CoversClass(PingBadRequestException::class),
    CoversClass(ApiResponseFactory::class),
    CoversClass(ApiResponse::class),
    CoversClass(ApiResponseLink::class),
    CoversClass(ApiResponseMeta::class),
    CoversClass(ApiResponseData::class),
    CoversClass(ApiResponseMessage::class),
    CoversClass(ListError::class),
    CoversClass(Error::class),
]
class ApiResponseGenericExceptionListenerTest extends HephUnitTestCase
{
    private ApiResponseGenericExceptionListener $listener;

    private HttpKernelInterface $kernel;

    private Request $request;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->listener = new ApiResponseGenericExceptionListener();
        $this->kernel = $this->createMock(HttpKernelInterface::class);
        $this->request = Request::create('/api/ping');
    }

    public function testOnKernelExceptionHandlesGenericApiResponseException(): void
    {
        $exceptionCustom = new RuntimeException('test runtime error');
        $event = new ExceptionEvent(
            $this->kernel,
            $this->request,
            HttpKernelInterface::MAIN_REQUEST,
            $exceptionCustom
        );

        $this->listener->onKernelException($event);

        // Vérifie que le listener a défini une réponse
        $response = $event->getResponse();
        self::assertNotNull($response, 'Response should not be null');
        self::assertInstanceOf(JsonResponse::class, $response);

        $content = $response->getContent();
        self::assertIsString($content);

        // Vérifie le contenu de la réponse JSON
        $responseData = json_decode($content, true);
        self::assertIsArray($responseData);
        self::assertSame(500, $responseData['statusCode']);
        self::assertSame('test runtime error', $responseData['message']);
        self::assertSame(null, $responseData['data']);
        self::assertSame(null, $responseData['meta']);
        self::assertSame(null, $responseData['links']);

        $expectedError = ['key' => 'error', 'message' => 'test runtime error'];
        self::assertContains($expectedError, $responseData['errors']);
    }

    public function testOnKernelExceptionHandlesGenericApiResponseExceptionWithLessMessage(): void
    {
        $statusTexts = Response::$statusTexts;

        $exceptionCustom = new RuntimeException('');
        $event = new ExceptionEvent(
            $this->kernel,
            $this->request,
            HttpKernelInterface::MAIN_REQUEST,
            $exceptionCustom
        );

        $this->listener->onKernelException($event);

        // Vérifie que le listener a défini une réponse
        $response = $event->getResponse();
        self::assertNotNull($response, 'Response should not be null');
        self::assertInstanceOf(JsonResponse::class, $response);

        $content = $response->getContent();
        self::assertIsString($content);

        // Vérifie le contenu de la réponse JSON
        $responseData = json_decode($content, true);
        self::assertIsArray($responseData);
        self::assertSame(500, $responseData['statusCode']);
        self::assertSame($statusTexts[500], $responseData['message']);
        self::assertSame(null, $responseData['data']);
        self::assertSame(null, $responseData['meta']);
        self::assertSame(null, $responseData['links']);

        $expectedError = ['key' => 'error', 'message' => $statusTexts[500]];
        self::assertContains($expectedError, $responseData['errors']);
    }

    /*
     * @throws Exception
     */
    public function testOnKernelExceptionIgnoresNonApiResponseGenericException(): void
    {
        $exception = new PingBadRequestException();

        $event = new ExceptionEvent(
            $this->kernel,
            $this->request,
            HttpKernelInterface::MAIN_REQUEST,
            $exception
        );

        $this->listener->onKernelException($event);

        // Vérifie que le listener n'a pas défini de réponse
        self::assertNull($event->getResponse());
    }

    public function testOnKernelExceptionSkipsWhenControllerIsNotApi(): void
    {
        // Simule un contrôleur qui n'est pas dans le namespace Heph\Controller\Api
        $this->request->attributes->set('_controller', 'Heph\\Controller\\Console\\SomeCommand');

        $exception = new RuntimeException('test runtime error');
        $event = new ExceptionEvent(
            $this->kernel,
            $this->request,
            HttpKernelInterface::MAIN_REQUEST,
            $exception
        );

        $this->listener->onKernelException($event);

        // Vérifie que la réponse n'a pas été définie
        self::assertNull($event->getResponse());
    }
}
