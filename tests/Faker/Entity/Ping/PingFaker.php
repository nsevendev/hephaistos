<?php

declare(strict_types=1);

namespace Heph\Tests\Faker\Entity\Ping;

use Heph\Entity\Ping\Ping;
use Heph\Entity\Ping\ValueObject\PingMessage;
use Heph\Entity\Ping\ValueObject\PingStatus;
use Heph\Infrastructure\ApiResponse\Exception\Custom\Ping\PingInvalidArgumentException;

final class PingFaker
{
    /**
     * @throws PingInvalidArgumentException
     */
    public static function new(): Ping
    {
        return new Ping(
            status: PingStatus::fromValue(200),
            message: PingMessage::fromValue('Le ping à réussi')
        );
    }

    /**
     * @throws PingInvalidArgumentException
     */
    public static function withMessageMoreLonger(): Ping
    {
        return new Ping(
            status: PingStatus::fromValue(200),
            message: PingMessage::fromValue('Le message du ping est trop long Le message du ping est trop long Le message du ping est trop long Le message du ping est trop long Le message du ping est trop long Le message du ping est trop long Le message du ping est trop long Le message du ping est trop long')
        );
    }

    /**
     * @throws PingInvalidArgumentException
     */
    public static function withMessageEmpty(): Ping
    {
        return new Ping(
            status: PingStatus::fromValue(200),
            message: PingMessage::fromValue('')
        );
    }
}
