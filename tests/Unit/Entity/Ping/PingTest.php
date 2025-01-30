<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Entity\Ping;

use DateTimeImmutable;
use Heph\Entity\Ping\Ping;
use Heph\Tests\Faker\Entity\Ping\PingFaker;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(Ping::class)]
class PingTest extends HephUnitTestCase
{
    public function testEntityInitialization(): void
    {
        $status = 200;
        $message = 'Le ping à réussi';

        $ping = PingFaker::new();

        self::assertSame($status, $ping->status());
        self::assertSame($message, $ping->message());
        self::assertNotNull($ping->createdAt());
        self::assertNotNull($ping->updatedAt());
    }

    public function testEntitySetters(): void
    {
        $ping = PingFaker::new();

        $newDateUpdated = new DateTimeImmutable();
        $ping->setUpdatedAt($newDateUpdated);

        self::assertSame($newDateUpdated, $ping->updatedAt());
    }
}
