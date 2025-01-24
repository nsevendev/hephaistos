<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Entity\EngineRemap;

use Heph\Entity\EngineRemap\EngineRemapEntity;
use Heph\Entity\Shared\Type\Uid;
use Heph\Infrastructure\Doctrine\Type\UidType;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(EngineRemapEntity::class), CoversClass(Uid::class), CoversClass(UidType::class)]
class EngineRemapEntityTest extends HephUnitTestCase {}
