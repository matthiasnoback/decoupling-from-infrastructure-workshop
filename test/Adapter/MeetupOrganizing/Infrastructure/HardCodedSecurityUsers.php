<?php
declare(strict_types=1);

namespace Test\Adapter\MeetupOrganizing\Infrastructure;

use MeetupOrganizing\Application\Users\CouldNotFindSecurityUser;
use MeetupOrganizing\Application\Users\SecurityUsers;
use MeetupOrganizing\Application\Users\SecurityUser;

final class HardCodedSecurityUsers implements SecurityUsers
{
    public const ORGANIZER_ID = 'e5c53d97-3c09-4b84-b376-8c7f3bdf2622';

    public function getByUsername(string $username): SecurityUser
    {
        if ($username === 'Organizer') {
            return new SecurityUser(
                self::ORGANIZER_ID,
                'Organizer'
            );
        }

        throw CouldNotFindSecurityUser::withUsername($username);
    }
}
