<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\PHPUnit;

use Heph\Tests\Extension\Event\FixedRandomSeedHandler;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(FixedRandomSeedHandler::class)]
final class RandomSeedTest extends HephUnitTestCase
{
    /**
     * This tests that the random seed is set correctly for each test.
     *
     * @see \Heph\Tests\Extension\Event\FixedRandomSeedHandler::notify()
     */
    public function testRandomIntegerIsAlwaysTheSame(): void
    {
        $value = mt_rand();

        self::assertNotSame(3053395, $value);
    }
}
