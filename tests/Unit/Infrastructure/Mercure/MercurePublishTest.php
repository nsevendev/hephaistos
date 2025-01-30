<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Infrastructure\Mercure;

use Heph\Infrastructure\ApiResponse\Exception\Custom\Mercure\MercureInvalidArgumentException;
use Heph\Infrastructure\ApiResponse\Exception\Error\Error;
use Heph\Infrastructure\Mercure\MercurePublish;
use Heph\Tests\Unit\HephUnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Exception;
use Symfony\Component\Mercure\HubInterface;

/**
 * il y a que le test pour le throw, car le reste de la class est tester dans le controller, handler etc ...
 */
#[
    CoversClass(MercurePublish::class),
    CoversClass(MercureInvalidArgumentException::class),
    CoversClass(Error::class),
]
class MercurePublishTest extends HephUnitTestCase
{
    /**
     * @throws Exception
     */
    public function testPublishThrowsExceptionOnInvalidJson(): void
    {
        // Création d'un mock pour HubInterface, car nous ne testons pas sa fonctionnalité ici
        $hubMock = $this->createMock(HubInterface::class);

        // Instanciation de la classe à tester
        $mercurePublish = new MercurePublish($hubMock);

        // Données invalides (les objets PHP circulaires ne peuvent pas être encodés en JSON)
        $invalidData = ['key' => tmpfile()]; // tmpfile() est un exemple de ressource non encodable

        $this->expectException(MercureInvalidArgumentException::class);
        $this->expectExceptionMessage('Données invalides à publier');

        $mercurePublish->publish('/test', $invalidData);
    }
}
