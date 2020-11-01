<?php
declare(strict_types=1);

namespace DevPro\Application\Users;

final class CreateUser
{
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function name(): string
    {
        return $this->name;
    }
}
