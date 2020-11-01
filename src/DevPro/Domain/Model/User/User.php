<?php
declare(strict_types=1);

namespace DevPro\Domain\Model\User;

use Assert\Assert;
use DevPro\Domain\Model\Common\EventRecordingCapabilities;

final class User
{
    use EventRecordingCapabilities;

    private UserId $userId;
    private string $name;

    private function __construct(UserId $userId, string $name)
    {
        Assert::that($name)->notEmpty('The name of a user should not be empty');

        $this->userId = $userId;
        $this->name = $name;
    }

    public static function create(UserId $userId, string $name): self
    {
        return new self($userId, $name);
    }

    public function userId(): UserId
    {
        return $this->userId;
    }
}
