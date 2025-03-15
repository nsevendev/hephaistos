<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Entity\Ping\Dto;

use Heph\Entity\Ping\Dto\PingCreateDto;
use Heph\Entity\Ping\ValueObject\PingMessage;
use Heph\Entity\Ping\ValueObject\PingStatus;
use Heph\Tests\Faker\Dto\Ping\PingCreateDtoFaker;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(PingCreateDto::class), CoversClass(PingStatus::class), CoversClass(PingMessage::class)]
class PingEntityCreateDtoTest extends HephUnitTestCase
{
    public function testPingEntityCreateDto(): void
    {
        $pingEntityCreateDto = PingCreateDtoFaker::new();

        self::assertNotNull($pingEntityCreateDto);

        self::assertInstanceOf(PingCreateDto::class, $pingEntityCreateDto);

        self::assertSame(200, $pingEntityCreateDto->status);
        self::assertSame('Le ping à réussi en faker', $pingEntityCreateDto->message);

        self::assertSame('200', (string) $pingEntityCreateDto->status);
        self::assertSame('Le ping à réussi en faker', (string) $pingEntityCreateDto->message);
    }

    public function testPingEntityCreateDtoWithFunctionNew(): void
    {
        $pingEntityCreateDto = PingCreateDto::new(
            200,
            'Le ping à réussi en faker'
        );

        self::assertNotNull($pingEntityCreateDto);

        self::assertInstanceOf(PingCreateDto::class, $pingEntityCreateDto);

        self::assertSame(200, $pingEntityCreateDto->status);
        self::assertSame('Le ping à réussi en faker', $pingEntityCreateDto->message);

        self::assertSame('200', (string) $pingEntityCreateDto->status);
        self::assertSame('Le ping à réussi en faker', (string) $pingEntityCreateDto->message);
    }
}
