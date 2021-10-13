<?php
declare(strict_types=1);

namespace MeetupOrganizing\Domain\Model\User;

use RuntimeException;

interface UserRepository
{
    public function save(User $user): void;

    /**
     * @throws RuntimeException When the entity could not be found
     */
    public function getById(UserId $userId): User;

    public function nextIdentity(): UserId;
}
