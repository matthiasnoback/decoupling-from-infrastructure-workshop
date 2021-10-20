<?php
declare(strict_types=1);

namespace MeetupOrganizing\Application\Users;

use MeetupOrganizing\Domain\Model\User\UserId;
use RuntimeException;

final class CouldNotFindUser extends RuntimeException
{
    public static function withId(UserId $userId): self
    {
        return new self(
            sprintf('Could not find user with ID "%s"', $userId->asString())
        );
    }

    public static function withUsername(string $name): self
    {
        return new self(
            sprintf('Could not find user with name "%s"', $name)
        );
    }
}
