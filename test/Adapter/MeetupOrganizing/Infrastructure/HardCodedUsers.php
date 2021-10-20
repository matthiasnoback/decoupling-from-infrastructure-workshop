<?php
declare(strict_types=1);

namespace Test\Adapter\MeetupOrganizing\Infrastructure;

use MeetupOrganizing\Application\Users\CouldNotFindUser;
use MeetupOrganizing\Application\Users\Users;
use MeetupOrganizing\Application\Users\User;
use MeetupOrganizing\Domain\Model\User\UserId;

final class HardCodedUsers implements Users
{
    public const ORGANIZER_ID = 'e5c53d97-3c09-4b84-b376-8c7f3bdf2622';
    public const USER_ID = '7c78026b-47f6-4e05-b2e4-4270d7c567e1';

    private const RECORDS = [
        [
            'userId' => self::ORGANIZER_ID,
            'username' => 'Organizer'
        ],
        [
            'userId' => self::USER_ID,
            'username' => 'User'
        ]
    ];

    public function getByUsername(string $username): User
    {
        foreach (self::RECORDS as $record) {
            if ($record['username'] === $username) {
                return User::fromDatabaseRecord($record);
            }
        }

        throw CouldNotFindUser::withUsername($username);
    }

    public function getById(UserId $userId): User
    {
        foreach (self::RECORDS as $record) {
            if ($record['userId'] === $userId->asString()) {
                return User::fromDatabaseRecord($record);
            }
        }

        throw CouldNotFindUser::withId($userId);
    }
}
