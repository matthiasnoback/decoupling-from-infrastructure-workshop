<?php
declare(strict_types=1);

namespace Test\Adapter\DevPro\Infrastructure;

use BadMethodCallException;
use DevPro\Application\ApplicationInterface;
use DevPro\Application\ScheduleTraining;
use DevPro\Application\UpcomingTraining;
use DevPro\Application\Users\CreateOrganizer;
use DevPro\Application\Users\CreateUser;
use DevPro\Domain\Model\Training\TrainingId;
use DevPro\Domain\Model\User\UserId;

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

    public function scheduleTraining(ScheduleTraining $command): TrainingId
    {
        $this->recordThatCommandWasSent($command);

        return TrainingId::fromString('dcf17716-12f2-43d0-a177-62b49b19dfd1');
    }

    public function findAllUpcomingTrainings(): array
    {
        return [
            new UpcomingTraining('Decoupling from infrastructure'),
            new UpcomingTraining('Propel for advanced users'),
        ];
    }

    private function recordThatCommandWasSent(object $command): void
    {
        header(self::COMMAND_SENT_HEADER . ': ' . base64_encode(serialize($command)));
    }
}
