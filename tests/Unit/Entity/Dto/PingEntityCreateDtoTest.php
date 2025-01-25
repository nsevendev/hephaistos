<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Entity\Dto;

use Heph\Entity\Ping\Dto\PingEntityCreateDto;
use Heph\Entity\Ping\ValueObject\PingMessage;
use Heph\Entity\Ping\ValueObject\PingStatus;
use Heph\Tests\Faker\Dto\Ping\PingEntityCreateDtoFaker;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(PingEntityCreateDto::class), CoversClass(PingStatus::class), CoversClass(PingMessage::class)]
class PingEntityCreateDtoTest extends HephUnitTestCase
{
    public function testPingEntityCreateDto(): void
    {
        $pingEntityCreateDto = PingEntityCreateDtoFaker::new();

        self::assertNotNull($pingEntityCreateDto);

        self::assertInstanceOf(PingEntityCreateDto::class, $pingEntityCreateDto);
        self::assertInstanceOf(PingStatus::class, $pingEntityCreateDto->status());
        self::assertInstanceOf(PingMessage::class, $pingEntityCreateDto->message());

        self::assertSame(200, $pingEntityCreateDto->status()->value());
        self::assertSame('Le ping à réussi en faker', $pingEntityCreateDto->message()->value());

        self::assertSame('200', (string) $pingEntityCreateDto->status());
        self::assertSame('Le ping à réussi en faker', (string) $pingEntityCreateDto->message());
    }

    public function testPingEntityCreateDtoWithFunctionNew(): void
    {
        $pingEntityCreateDto = PingEntityCreateDto::new(
            200,
            'Le ping à réussi en faker'
        );

        self::assertNotNull($pingEntityCreateDto);

        self::assertInstanceOf(PingEntityCreateDto::class, $pingEntityCreateDto);
        self::assertInstanceOf(PingStatus::class, $pingEntityCreateDto->status());
        self::assertInstanceOf(PingMessage::class, $pingEntityCreateDto->message());

        self::assertSame(200, $pingEntityCreateDto->status()->value());
        self::assertSame('Le ping à réussi en faker', $pingEntityCreateDto->message()->value());

        self::assertSame('200', (string) $pingEntityCreateDto->status());
        self::assertSame('Le ping à réussi en faker', (string) $pingEntityCreateDto->message());
    }
}
