<?php
declare(strict_types=1);

namespace DevPro\Domain\Model\User;

use RuntimeException;

final class CouldNotFindUser extends RuntimeException
{
    public static function withId(UserId $userId): self
    {
        return new self(
            'Could not find user with ID ' . $userId->asString()
        );
    }
}
