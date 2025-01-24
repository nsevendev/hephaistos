<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Entity\EngineRemap;

use DateTimeImmutable;
use Heph\Entity\EngineRemap\EngineRemapEntity;
use Heph\Entity\Shared\Type\Uid;
use Heph\Infrastructure\Doctrine\Type\UidType;
use Heph\Tests\Faker\Entity\EngineRemap\EngineRemapEntityFaker;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(EngineRemapEntity::class), CoversClass(Uid::class), CoversClass(UidType::class)]
class EngineRemapEntityTest extends HephUnitTestCase
{
    public function testNew(): void
    {
        $entity = EngineRemapEntityFaker::new();

        self::assertInstanceOf(EngineRemapEntity::class, $entity);
        self::assertNotNull($entity->id());
        self::assertInstanceOf(DateTimeImmutable::class, $entity->createdAt());
        self::assertInstanceOf(DateTimeImmutable::class, $entity->updatedAt());
        self::assertNotNull($entity->getInfoDescriptionModel());
    }
}
