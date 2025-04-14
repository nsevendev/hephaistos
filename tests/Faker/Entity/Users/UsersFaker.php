<?php

declare(strict_types=1);

namespace Heph\Tests\Faker\Entity\Users;

use Heph\Entity\Users\Users;
use Heph\Entity\Users\ValueObject\UsersPassword;
use Heph\Entity\Users\ValueObject\UsersRole;
use Heph\Entity\Users\ValueObject\UsersUsername;
use Heph\Infrastructure\ApiResponse\Exception\Custom\Users\UsersInvalidArgumentException;

final class UsersFaker
{
    /**
     * @throws UsersInvalidArgumentException
     */
    public static function new(): Users
    {
        return new Users(
            password: UsersPassword::fromValue('password'),
            username: UsersUsername::fromValue('username'),
            role: UsersRole::fromValue('admin'),
        );
    }

    /**
     * @throws UsersInvalidArgumentException
     */
    public static function withPasswordMoreLonger(): Users
    {
        return new Users(
            password: UsersPassword::fromValue('withPasswordMoreLongerwithPasswordMoreLongerwithPasswordMoreLongerwithPasswordMoreLongerwithPasswordMoreLongerwithPasswordMoreLongerwithPasswordMoreLongerwithPasswordMoreLongerwithPasswordMoreLongerwithPasswordMoreLongerwithPasswordMoreLongerwithPasswordMoreLongerwithPasswordMoreLongerwithPasswordMoreLongerwithPasswordMoreLongerwithPasswordMoreLongerwithPasswordMoreLongerwithPasswordMoreLongerwithPasswordMoreLongerwithPasswordMoreLongerwithPasswordMoreLongerwithPasswordMoreLongerwithPasswordMoreLongerwithPasswordMoreLongerwithPasswordMoreLongerwithPasswordMoreLongerwithPasswordMoreLongerwithPasswordMoreLonger'),
            username: UsersUsername::fromValue('username'),
            role: UsersRole::fromValue('admin'),
        );
    }

    /**
     * @throws UsersInvalidArgumentException
     */
    public static function withPasswordEmpty(): Users
    {
        return new Users(
            password: UsersPassword::fromValue(''),
            username: UsersUsername::fromValue('username'),
            role: UsersRole::fromValue('admin'),
        );
    }

    /**
     * @throws UsersInvalidArgumentException
     */
    public static function withUsernameMoreLonger(): Users
    {
        return new Users(
            password: UsersPassword::fromValue('password'),
            username: UsersUsername::fromValue('withUsernameMoreLongerwithUsernameMoreLongerwithUsernameMoreLongerwithUsernameMoreLongerwithUsernameMoreLongerwithUsernameMoreLongerwithUsernameMoreLongerwithUsernameMoreLongerwithUsernameMoreLongerwithUsernameMoreLongerwithUsernameMoreLongerwithUsernameMoreLongerwithUsernameMoreLongerwithUsernameMoreLongerwithUsernameMoreLongerwithUsernameMoreLongerwithUsernameMoreLongerwithUsernameMoreLongerwithUsernameMoreLongerwithUsernameMoreLongerwithUsernameMoreLongerwithUsernameMoreLongerwithUsernameMoreLongerwithUsernameMoreLongerwithUsernameMoreLongerwithUsernameMoreLongerwithUsernameMoreLongerwithUsernameMoreLongerwithUsernameMoreLongerwithUsernameMoreLongerwithUsernameMoreLongerwithUsernameMoreLongerwithUsernameMoreLongerwithUsernameMoreLongerwithUsernameMoreLongerwithUsernameMoreLongerwithUsernameMoreLongerwithUsernameMoreLongerwithUsernameMoreLongerwithUsernameMoreLongerwithUsernameMoreLongerwithUsernameMoreLongerwithUsernameMoreLongerwithUsernameMoreLongerwithUsernameMoreLongerwithUsernameMoreLongerwithUsernameMoreLongerwithUsernameMoreLongerwithUsernameMoreLongerwithUsernameMoreLongerwithUsernameMoreLongerwithUsernameMoreLongerwithUsernameMoreLongerwithUsernameMoreLongerwithUsernameMoreLongerwithUsernameMoreLongerwithUsernameMoreLongerwithUsernameMoreLongerwithUsernameMoreLongerwithUsernameMoreLongerwithUsernameMoreLongerwithUsernameMoreLongerwithUsernameMoreLongerwithUsernameMoreLongerwithUsernameMoreLongerwithUsernameMoreLongerwithUsernameMoreLongerwithUsernameMoreLonger'),
            role: UsersRole::fromValue('admin'),
        );
    }

    /**
     * @throws UsersInvalidArgumentException
     */
    public static function withUsernameEmpty(): Users
    {
        return new Users(
            password: UsersPassword::fromValue('password'),
            username: UsersUsername::fromValue(''),
            role: UsersRole::fromValue('admin'),
        );
    }

    /**
     * @throws UsersInvalidArgumentException
     */
    public static function withRoleInvalid(): Users
    {
        return new Users(
            password: UsersPassword::fromValue('password'),
            username: UsersUsername::fromValue('username'),
            role: UsersRole::fromValue('test'),
        );
    }

    /**
     * @throws UsersInvalidArgumentException
     */
    public static function withRoleEmpty(): Users
    {
        return new Users(
            password: UsersPassword::fromValue('password'),
            username: UsersUsername::fromValue('username'),
            role: UsersRole::fromValue(''),
        );
    }
}
