<?php
declare(strict_types=1);

namespace Test\Adapter\DevPro\Infrastructure;

use DevPro\Application\Users\GetSecurityUser;
use DevPro\Application\Users\SecurityUser;
use RuntimeException;

final class HardCodedGetSecurityUser implements GetSecurityUser
{
    public const ORGANIZER_ID = 'e5c53d97-3c09-4b84-b376-8c7f3bdf2622';

    public function byUsername(string $username): SecurityUser
    {
        if ($username === 'Organizer') {
            return new SecurityUser(
                self::ORGANIZER_ID,
                'Organizer'
            );
        }

        throw new RuntimeException(
            sprintf('Could not find user with name "%s"', $username)
        );
    }
}
