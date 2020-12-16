<?php
declare(strict_types=1);

namespace DevPro\Application;

use BadMethodCallException;
use DevPro\Application\Training\ScheduleTraining;
use DevPro\Application\Users\CreateOrganizer;
use DevPro\Application\Users\CreateUser;
use DevPro\Domain\Model\Training\TrainingId;
use DevPro\Domain\Model\User\UserId;
use DevPro\Infrastructure\AbstractServiceContainer;

final class Application implements ApplicationInterface
{
    private AbstractServiceContainer $container;

    public function __construct(AbstractServiceContainer $container)
    {
        $this->container = $container;
    }

    public function createUser(CreateUser $command): UserId
    {
        return $this->container->createUserHandler()->handle($command);
    }

    public function createOrganizer(CreateOrganizer $command): UserId
    {
        return $this->container->createOrganizerHandler()->handle($command);
    }

    public function scheduleTraining(ScheduleTraining $command): TrainingId
    {
        throw new BadMethodCallException('Not implemented');
    }
}
