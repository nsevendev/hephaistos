<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Infrastructure\ApiResponse\Exception\Custom;

use Heph\Infrastructure\ApiResponse\ApiResponse;
use Heph\Infrastructure\ApiResponse\ApiResponseFactory;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseData;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseLink;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseMessage;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseMeta;
use Heph\Infrastructure\ApiResponse\Exception\Custom\TermsConditionsArticle\TermsConditionsArticleBadRequestException;
use Heph\Infrastructure\ApiResponse\Exception\Error\Error;
use Heph\Infrastructure\ApiResponse\Exception\Error\ListError;
use Heph\Infrastructure\ApiResponse\Exception\Event\ApiResponseExceptionListener;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

#[
    CoversClass(ApiResponseExceptionListener::class),
    CoversClass(TermsConditionsArticleBadRequestException::class),
    CoversClass(ApiResponseFactory::class),
    CoversClass(ApiResponse::class),
    CoversClass(ApiResponseLink::class),
    CoversClass(ApiResponseMeta::class),
    CoversClass(ApiResponseData::class),
    CoversClass(ApiResponseMessage::class),
    CoversClass(ListError::class),
    CoversClass(Error::class),
]
class TermsConditionsArticleBadRequestExceptionTest extends HephUnitTestCase
{
    private ApiResponseExceptionListener $listener;
    private HttpKernelInterface $kernel;
    private Request $request;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->listener = new ApiResponseExceptionListener();
        $this->kernel = $this->createMock(HttpKernelInterface::class);
        $this->request = Request::create('/api/lm-quatre');
    }

    public function testOnKernelExceptionTermsConditionsArticleBadRequestException(): void
    {
        $exceptionCustom = new TermsConditionsArticleBadRequestException();
        $event = new ExceptionEvent(
            $this->kernel,
            $this->request,
            HttpKernelInterface::MAIN_REQUEST,
            $exceptionCustom
        );

        $this->listener->onKernelException($event);

        self::assertSame(400, $exceptionCustom->getStatusCode());

        $response = $event->getResponse();
        self::assertNotNull($response, 'Response should not be null');
        self::assertInstanceOf(JsonResponse::class, $response);

        $content = $response->getContent();
        self::assertIsString($content);

        $responseData = json_decode($content, true);
        self::assertIsArray($responseData);
        self::assertSame(400, $responseData['statusCode']);
        self::assertSame('Bad Request', $responseData['message']);
        self::assertSame(null, $responseData['data']);
        self::assertSame(null, $responseData['meta']);
        self::assertSame(null, $responseData['links']);

        $expectedError = ['key' => 'error', 'message' => 'Bad Request'];
        self::assertContains($expectedError, $responseData['errors']);
    }
}
