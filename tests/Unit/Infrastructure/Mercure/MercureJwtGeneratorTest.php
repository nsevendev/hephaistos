<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Infrastructure\Mercure;

use Heph\Infrastructure\ApiResponse\Exception\Custom\Mercure\MercureInvalidArgumentException;
use Heph\Infrastructure\ApiResponse\Exception\Error\Error;
use Heph\Infrastructure\Mercure\MercureJwtGenerator;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[
    CoversClass(MercureJwtGenerator::class),
    CoversClass(MercureInvalidArgumentException::class),
    CoversClass(Error::class),
]
class MercureJwtGeneratorTest extends HephUnitTestCase
{
    public function testGeneratePublisherTokenThrowsExceptionWhenSecretIsEmpty(): void
    {
        // Instancie la classe avec un secret vide
        $mercureJwtGenerator = new MercureJwtGenerator('');

        $this->expectException(MercureInvalidArgumentException::class);
        $this->expectExceptionMessage('Mercure secret is not set');

        $mercureJwtGenerator->generatePublisherToken(['/test']);
    }

    public function testGenerateSubscriberTokenThrowsExceptionWhenSecretIsEmpty(): void
    {
        // Instancie la classe avec un secret vide
        $mercureJwtGenerator = new MercureJwtGenerator('');

        $this->expectException(MercureInvalidArgumentException::class);
        $this->expectExceptionMessage('Mercure secret is not set');

        $mercureJwtGenerator->generateSubscriberToken(['/test']);
    }
}
