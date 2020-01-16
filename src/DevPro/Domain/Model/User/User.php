<?php
declare(strict_types=1);

namespace DevPro\Domain\Model\User;

use DevPro\Domain\Model\Common\EventRecordingCapabilities;

final class User
{
    use EventRecordingCapabilities;

    /**
     * @var UserId
     */
    private $userId;

    private function __construct(UserId $userId)
    {
        $this->userId = $userId;
    }

    public static function create(UserId $userId): self
    {
        return new self($userId);
    }

    public function userId(): UserId
    {
        return $this->userId;
    }
}
