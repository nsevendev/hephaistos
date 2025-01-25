<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Infrastructure\Serializer\Normalizer;

use Heph\Entity\Shared\ValueObject\TestValueObject;
use Heph\Infrastructure\Serializer\Normalizer\ValueObjectNormalizer;
use Heph\Infrastructure\Shared\Type\ValueObjectInterface;
use Heph\Tests\Unit\HephUnitTestCase;
use LogicException;
use PHPUnit\Framework\Attributes\CoversClass;
use stdClass;

#[
    CoversClass(ValueObjectNormalizer::class),
    CoversClass(TestValueObject::class)
]
class ValueObjectNormalizerTest extends HephUnitTestCase
{
    private ValueObjectNormalizer $normalizer;

    protected function setUp(): void
    {
        $this->normalizer = new ValueObjectNormalizer();
    }

    public function testNormalize(): void
    {
        $vo = TestValueObject::fromValue('test-value');
        $result = $this->normalizer->normalize($vo);

        $this->assertSame('test-value', $result, 'The value returned by normalize is not correct.');
    }

    public function testSupportsNormalization(): void
    {
        $vo = TestValueObject::fromValue('test-value');
        $notVo = new stdClass();

        $this->assertTrue(
            $this->normalizer->supportsNormalization($vo),
            'supportsNormalization should return true for a ValueObjectInterface instance.'
        );
        $this->assertFalse(
            $this->normalizer->supportsNormalization($notVo),
            'supportsNormalization should return false for an object that does not implement ValueObjectInterface.'
        );
    }

    public function testGetSupportedTypes(): void
    {
        $types = $this->normalizer->getSupportedTypes(null);

        // Vérifie que ValueObjectInterface est bien déclaré comme supporté
        $this->assertArrayHasKey(ValueObjectInterface::class, $types);
        $this->assertTrue($types[ValueObjectInterface::class]);
    }

    public function testDenormalize(): void
    {
        $result = $this->normalizer->denormalize('test-value', TestValueObject::class);

        $this->assertInstanceOf(
            TestValueObject::class,
            $result,
            'denormalize should return an instance of the ValueObject class.'
        );
        $this->assertSame(
            'test-value',
            $result->value(),
            'The value in the denormalized object is not correct.'
        );
    }

    public function testDenormalizeThrowsExceptionIfFromValueNotExists(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage("Cannot denormalize object of type stdClass without fromValue method.");

        $this->normalizer->denormalize('test-value', stdClass::class);
    }

    public function testSupportsDenormalization(): void
    {
        $this->assertTrue(
            $this->normalizer->supportsDenormalization([], TestValueObject::class),
            'supportsDenormalization should return true for a ValueObjectInterface implementation.'
        );
        $this->assertFalse(
            $this->normalizer->supportsDenormalization([], stdClass::class),
            'supportsDenormalization should return false for a class that does not implement ValueObjectInterface.'
        );
    }
}
