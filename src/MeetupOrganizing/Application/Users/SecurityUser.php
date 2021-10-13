<?php
declare(strict_types=1);

namespace MeetupOrganizing\Application\Users;

final class SecurityUser
{
    private string $id;
    private string $username;

    public function __construct(string $id, string $username)
    {
        $this->id = $id;
        $this->username = $username;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function username(): string
    {
        return $this->username;
    }
}
