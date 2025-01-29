<?php

declare(strict_types=1);

namespace Heph\Tests\Extension;

use Heph\Tests\Extension\Event\FixedRandomSeedHandler;
use PHPUnit\Runner\Extension\Extension;
use PHPUnit\Runner\Extension\Facade;
use PHPUnit\Runner\Extension\ParameterCollection;
use PHPUnit\TextUI\Configuration\Configuration;

final class FakerExtension implements Extension
{
    public function bootstrap(Configuration $configuration, Facade $facade, ParameterCollection $parameters): void
    {
        $facade->registerSubscribers(
            new FixedRandomSeedHandler(),
        );
    }
}
