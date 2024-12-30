<?php

declare(strict_types=1);

namespace Heph\Tests\Unit\Infrastructure\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Heph\Entity\Shared\Type\Uid;
use Heph\Entity\Shared\UidTest;
use Heph\Infrastructure\Doctrine\Type\UidType;
use Heph\Tests\Unit\HephUnitTestCase;
use LogicException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;

#[
    CoversClass(UidType::class),
    CoversClass(Uid::class),
    CoversClass(UidTest::class),
]
class UidTypeTest extends HephUnitTestCase
{
    private UidType $uidType;

    private AbstractPlatform|MockObject $platform;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->uidType = UidType::new(UidTest::class);

        /** @var AbstractPlatform&MockObject $platform */
        $platform = $this->createMock(AbstractPlatform::class);

        $this->platform = $platform;
    }

    public function testGetSqlDeclaration(): void
    {
        $this->platform
            ->method('getStringTypeDeclarationSQL')
            ->with(['length' => 32])
            ->willReturn('VARCHAR(32)');

        $sql = $this->uidType->getSQLDeclaration([], $this->platform);
        self::assertSame('VARCHAR(32)', $sql);
    }

    /**
     * @throws ConversionException
     */
    public function testConvertToPhpValue(): void
    {
        $value = '1234567890abcdef1234567890abcdef';
        $uid = $this->uidType->convertToPHPValue($value, $this->platform);
        self::assertInstanceOf(UidTest::class, $uid);
        self::assertSame($value, (string) $uid);
    }

    /**
     * @throws ConversionException
     */
    public function testConvertToPhpValueReturnsNullWhenValueIsNull(): void
    {
        $result = $this->uidType->convertToPHPValue(null, $this->platform);
        self::assertNull($result);
    }

    /**
     * @throws ConversionException
     */
    public function testConvertToPhpValueThrowsExceptionWhenValueIsNotString(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Database value must be a string');

        $this->uidType->convertToPHPValue(123, $this->platform); // Passe un entier au lieu d'une chaîne
    }

    /**
     * @throws ConversionException
     */
    public function testConvertToDatabaseValue(): void
    {
        $uid = UidTest::fromString('1234567890abcdef1234567890abcdef');
        $dbValue = $this->uidType->convertToDatabaseValue($uid, $this->platform);
        self::assertSame('1234567890abcdef1234567890abcdef', $dbValue);
    }

    /**
     * @throws ConversionException
     */
    public function testConvertToDatabaseValueReturnsNullWhenValueIsNull(): void
    {
        $result = $this->uidType->convertToDatabaseValue(null, $this->platform);
        self::assertNull($result);
    }

    /**
     * @throws ConversionException
     */
    public function testConvertToDatabaseValueThrowsExceptionWhenValueIsNotUid(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Value need to be an instance of Uid');

        $this->uidType->convertToDatabaseValue('invalid-uid', $this->platform); // Passe une chaîne au lieu d'une instance de Uid
    }

    public function testGetName(): void
    {
        $name = $this->uidType->getName();
        self::assertSame('app_uid', $name);
    }

    public function testStaticName(): void
    {
        $name = UidType::name();
        self::assertSame('app_uid', $name);
    }
}
