<?php

declare(strict_types=1);

namespace Heph\Fixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use ReflectionException;

abstract class AbstractFixture extends Fixture
{
    /**
     * @throws ReflectionException
     */
    final public function load(ObjectManager $manager): void
    {
        foreach ($this->supply($manager) as $object) {
            RecursiveEntityPersiste::persist($manager, $object);
        }

        $manager->flush();
    }

    /**
     * @return iterable<object>
     */
    abstract protected function supply(ObjectManager $manager): iterable;
}
