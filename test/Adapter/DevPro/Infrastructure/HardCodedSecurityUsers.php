<?php
declare(strict_types=1);

namespace Test\Adapter\DevPro\Infrastructure;

use DevPro\Application\Users\CouldNotFindSecurityUser;
use DevPro\Application\Users\SecurityUsers;
use DevPro\Application\Users\SecurityUser;

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
