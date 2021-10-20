<?php
declare(strict_types=1);

namespace MeetupOrganizing\Application\Users;

final class SecurityUser
{
    private string $userId;
    private string $username;

    public function __construct(string $userId, string $username)
    {
        $this->userId = $userId;
        $this->username = $username;
    }

    public function userId(): string
    {
        return $this->userId;
    }

    public function username(): string
    {
        return $this->username;
    }
}
