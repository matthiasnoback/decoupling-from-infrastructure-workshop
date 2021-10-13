<?php
declare(strict_types=1);

namespace MeetupOrganizing\Application\Users;

use MeetupOrganizing\Domain\Model\User\CouldNotFindUser;

interface SecurityUsers
{
    /**
     * @throws CouldNotFindUser When there is no user with the given username
     */
    public function getByUsername(string $username): SecurityUser;
}
