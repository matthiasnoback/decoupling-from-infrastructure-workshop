<?php
declare(strict_types=1);

namespace Test\Adapter\MeetupOrganizing\Infrastructure;

use MeetupOrganizing\Application\Users\CouldNotFindSecurityUser;
use MeetupOrganizing\Application\Users\SecurityUsers;
use MeetupOrganizing\Application\Users\SecurityUser;

final class HardCodedSecurityUsers implements SecurityUsers
{
    public const ORGANIZER_ID = 'e5c53d97-3c09-4b84-b376-8c7f3bdf2622';
    public const USER_ID = '7c78026b-47f6-4e05-b2e4-4270d7c567e1';

    public function getByUsername(string $username): SecurityUser
    {
        if ($username === 'Organizer') {
            return new SecurityUser(
                self::ORGANIZER_ID,
                'Organizer'
            );
        }

        if ($username === 'User') {
            return new SecurityUser(
                self::USER_ID,
                'User'
            );
        }

        throw CouldNotFindSecurityUser::withUsername($username);
    }
}
