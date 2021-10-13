<?php
declare(strict_types=1);

namespace Test\Adapter\MeetupOrganizing\Infrastructure;

use BadMethodCallException;
use MeetupOrganizing\Application\ApplicationInterface;
use MeetupOrganizing\Application\Users\CreateOrganizer;
use MeetupOrganizing\Application\Users\CreateUser;
use MeetupOrganizing\Domain\Model\User\UserId;

final class ApplicationSpy implements ApplicationInterface
{
    const COMMAND_SENT_HEADER = 'X-Command-Sent';

    public function __construct()
    {
    }

    public function createUser(CreateUser $command): UserId
    {
        $this->recordThatCommandWasSent($command);

        return UserId::fromString('5e4e7be5-b46d-4b25-bcb7-affa8ec37655');
    }

    public function createOrganizer(CreateOrganizer $command): UserId
    {
        throw new BadMethodCallException('Not implemented');
    }

    private function recordThatCommandWasSent(object $command): void
    {
        header(self::COMMAND_SENT_HEADER . ': ' . base64_encode(serialize($command)));
    }
}
