<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Entity\Ping;

use DateTimeImmutable;
use Heph\Entity\Ping\Ping;
use Heph\Entity\Ping\ValueObject\PingMessage;
use Heph\Entity\Ping\ValueObject\PingStatus;
use Heph\Infrastructure\ApiResponse\Exception\Custom\AbstractApiResponseException;
use Heph\Infrastructure\ApiResponse\Exception\Custom\Ping\PingInvalidArgumentException;
use Heph\Infrastructure\ApiResponse\Exception\Error\Error;
use Heph\Infrastructure\Doctrine\Types\Ping\PingMessageType;
use Heph\Infrastructure\Doctrine\Types\Ping\PingStatusType;
use Heph\Tests\Faker\Entity\Ping\PingFaker;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[
    CoversClass(Ping::class),
    CoversClass(PingMessage::class),
    CoversClass(PingMessageType::class),
    CoversClass(PingStatus::class),
    CoversClass(PingStatusType::class),
    CoversClass(AbstractApiResponseException::class),
    CoversClass(PingInvalidArgumentException::class),
    CoversClass(Error::class),
]
class PingTest extends HephUnitTestCase
{
    /**
     * @throws PingInvalidArgumentException
     */
    public function testEntityInitialization(): void
    {
        $status = 200;
        $message = 'Le ping à réussi';

        $ping = PingFaker::new();

        self::assertSame($status, $ping->status()->value());
        self::assertSame($message, $ping->message()->value());
        self::assertSame($status, $ping->status()->jsonSerialize());
        self::assertSame($message, $ping->message()->jsonSerialize());
        self::assertSame((string) $status, (string) $ping->status());
        self::assertSame($message, (string) $ping->message());
        self::assertNotNull($ping->createdAt());
        self::assertNotNull($ping->updatedAt());
    }

    /**
     * @throws PingInvalidArgumentException
     */
    public function testEntitySetters(): void
    {
        $ping = PingFaker::new();

        $newDateUpdated = new DateTimeImmutable();
        $ping->setUpdatedAt($newDateUpdated);

        self::assertSame($newDateUpdated, $ping->updatedAt());
    }

    public function testEntityWithMessageMoreLonger(): void
    {
        $this->expectException(PingInvalidArgumentException::class);

        $ping = PingFaker::withMessageMoreLonger();
    }

    public function testEntityWithMessageEmpty(): void
    {
        $this->expectException(PingInvalidArgumentException::class);

        $ping = PingFaker::withMessageEmpty();
    }
}
