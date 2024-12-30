<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Entity\Ping;

use Heph\Entity\Ping\PingEntity;
use Heph\Entity\Shared\Type\Uid;
use Heph\Infrastructure\Doctrine\Type\UidType;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(PingEntity::class), CoversClass(Uid::class), CoversClass(UidType::class)]
class PingEntityTest extends HephUnitTestCase
{
    public function testEntityInitialization(): void
    {
        $status = 200;
        $message = 'Ping success';

        $pingEntity = new PingEntity($status, $message);

        self::assertSame($status, $pingEntity->status());
        self::assertSame($message, $pingEntity->message());
    }

    public function testEntityWithNullValues(): void
    {
        $pingEntity = new PingEntity(null, null);

        self::assertNull($pingEntity->status());
        self::assertNull($pingEntity->message());
    }
}
