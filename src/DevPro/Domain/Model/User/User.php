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

    private function __construct()
    {
    }

    public static function create(UserId $userId, string $name): self
    {
        $instance = new self();

        Assert::that($name)->notEmpty('The name of a user should not be empty');

        $instance->userId = $userId;
        $instance->name = $name;

        return $instance;
    }

    public function userId(): UserId
    {
        return $this->userId;
    }

    public function getDatabaseRecordData(): array
    {
        return [
            'id' => $this->userId->asString(),
            'name' => $this->name
        ];
    }

    public static function fromDatabaseRecord(array $data): self
    {
        $instance = new self();

        $instance->userId = UserId::fromString($data['id']);
        $instance->name = $data['name'];

        return $instance;
    }
}
