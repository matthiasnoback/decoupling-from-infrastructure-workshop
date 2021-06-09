<?php
declare(strict_types=1);

namespace DevPro\Application;

use Common\EventDispatcher\EventDispatcher;
use DevPro\Application\Users\CreateOrganizer;
use DevPro\Application\Users\CreateOrganizerHandler;
use DevPro\Application\Users\CreateUser;
use DevPro\Application\Users\CreateUserHandler;
use DevPro\Domain\Model\Training\Training;
use DevPro\Domain\Model\Training\TrainingId;
use DevPro\Domain\Model\Training\TrainingRepository;
use DevPro\Domain\Model\User\UserId;

final class Application implements ApplicationInterface
{
    private CreateUserHandler $createUserHandler;
    private CreateOrganizerHandler $createOrganizerHandler;
    private TrainingRepository $trainingRepository;
    private EventDispatcher $eventDispatcher;

    public function __construct(
        CreateUserHandler $createUserHandler,
        CreateOrganizerHandler $createOrganizerHandler,
        TrainingRepository $trainingRepository,
        EventDispatcher $eventDispatcher
    ) {
        $this->createUserHandler = $createUserHandler;
        $this->createOrganizerHandler = $createOrganizerHandler;
        $this->trainingRepository = $trainingRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function createUser(CreateUser $command): UserId
    {
        return $this->createUserHandler->handle($command);
    }

    public function createOrganizer(CreateOrganizer $command): UserId
    {
        return $this->createOrganizerHandler->handle($command);
    }

    public function findAllUpcomingTrainings(): array
    {
        return [];
    }

    public function scheduleTraining(ScheduleTraining $command): TrainingId
    {
        $training = Training::schedule(
            $this->trainingRepository->nextIdentity(),
            $command->organizerId(),
            $command->country(),
            $command->title(),
            $command->scheduledDate()
        );
        $this->trainingRepository->save($training);

        $this->eventDispatcher->dispatchAll($training->releaseEvents());

        return $training->trainingId();
    }
}
