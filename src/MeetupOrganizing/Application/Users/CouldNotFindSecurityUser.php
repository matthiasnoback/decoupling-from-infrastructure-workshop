<?php
declare(strict_types=1);

namespace MeetupOrganizing\Application\Users;

use RuntimeException;

final class CouldNotFindSecurityUser extends RuntimeException
{
    public static function withUsername(string $name): self
    {
        return new self(
            sprintf('Could not find security user with name "%s"', $name)
        );
    }
}
