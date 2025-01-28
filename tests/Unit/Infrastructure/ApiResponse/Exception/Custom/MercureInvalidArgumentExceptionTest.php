<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Infrastructure\ApiResponse\Exception\Custom;

use Heph\Infrastructure\ApiResponse\Exception\Custom\Mercure\MercureInvalidArgumentException;
use Heph\Infrastructure\ApiResponse\Exception\Error\Error;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\HttpFoundation\Response;

#[CoversClass(MercureInvalidArgumentException::class), CoversClass(Error::class)]
class MercureInvalidArgumentExceptionTest extends HephUnitTestCase
{
    public function testIfMessageIsPopulatedFromStatusTexts(): void
    {
        // Cas où $getMessage est vide et $statusCode existe dans Response::$statusTexts
        $exception = new MercureInvalidArgumentException(
            getMessage: '',
            statusCode: Response::HTTP_BAD_REQUEST
        );

        $this->assertEquals(Response::$statusTexts[Response::HTTP_BAD_REQUEST], $exception->getMessage());
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $exception->getStatusCode());
    }
}
