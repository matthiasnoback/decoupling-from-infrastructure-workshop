<?php
declare(strict_types=1);

namespace DevPro\Application;

use BadMethodCallException;
use DevPro\Application\Users\CreateOrganizer;
use DevPro\Application\Users\CreateOrganizerHandler;
use DevPro\Application\Users\CreateUser;
use DevPro\Application\Users\CreateUserHandler;
use DevPro\Domain\Model\Training\TrainingId;
use DevPro\Domain\Model\User\UserId;

final class Application implements ApplicationInterface
{
    private CreateUserHandler $createUserHandler;
    private CreateOrganizerHandler $createOrganizerHandler;

    public function __construct(
        CreateUserHandler $createUserHandler,
        CreateOrganizerHandler $createOrganizerHandler
    ) {
        $this->createUserHandler = $createUserHandler;
        $this->createOrganizerHandler = $createOrganizerHandler;
    }

    public function createUser(CreateUser $command): UserId
    {
        return $this->createUserHandler->handle($command);
    }

    public function createOrganizer(CreateOrganizer $command): UserId
    {
        return $this->createOrganizerHandler->handle($command);
    }

    public function scheduleTraining(ScheduleTraining $command): TrainingId
    {
        throw new BadMethodCallException('Not implemented yet');
    }
}
