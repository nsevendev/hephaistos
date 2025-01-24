<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Entity\Ping;

use DateTimeImmutable;
use Heph\Entity\Ping\PingEntity;
use Heph\Entity\Shared\Type\Uid;
use Heph\Infrastructure\Doctrine\Type\UidType;
use Heph\Tests\Faker\Entity\Ping\PingEntityFaker;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(PingEntity::class), CoversClass(Uid::class), CoversClass(UidType::class)]
class PingEntityTest extends HephUnitTestCase
{
    public function testEntityInitialization(): void
    {
        $status = 200;
        $message = 'Le ping à réussi';

        $pingEntity = PingEntityFaker::new();

        self::assertSame($status, $pingEntity->status());
        self::assertSame($message, $pingEntity->message());
        self::assertNotNull($pingEntity->createdAt());
        self::assertNotNull($pingEntity->updatedAt());
    }

    public function testEntityWithEmptyValues(): void
    {
        $pingEntity = PingEntityFaker::newWithEmptyValues();

        self::assertSame(0, $pingEntity->status());
        self::assertSame('', $pingEntity->message());
        self::assertNotNull($pingEntity->createdAt());
        self::assertNotNull($pingEntity->updatedAt());

        $newDateUpdated = new DateTimeImmutable();
        $pingEntity->setUpdatedAt($newDateUpdated);

        self::assertSame($newDateUpdated, $pingEntity->updatedAt());
    }
}
