<?php
declare(strict_types=1);

namespace MeetupOrganizing\Application\Users;

final class CreateUser
{
    private string $username;

    public function __construct(string $username)
    {
        $this->username = $username;
    }

    public function username(): string
    {
        return $this->username;
    }
}
