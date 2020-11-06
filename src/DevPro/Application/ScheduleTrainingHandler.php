<?php
declare(strict_types=1);

namespace DevPro\Application;

use Common\EventDispatcher\EventDispatcher;
use DevPro\Domain\Model\Training\Training;
use DevPro\Domain\Model\Training\TrainingId;
use DevPro\Domain\Model\Training\TrainingRepository;
use DevPro\Domain\Model\User\UserRepository;

final class ScheduleTrainingHandler
{
    private TrainingRepository $trainingRepository;
    private UserRepository $userRepository;
    private EventDispatcher $eventDispatcher;

    public function __construct(
        UserRepository $userRepository,
        TrainingRepository $trainingRepository,
        EventDispatcher $eventDispatcher
    ) {
        $this->userRepository = $userRepository;
        $this->trainingRepository = $trainingRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function handle(ScheduleTraining $command): TrainingId
    {
        $organizer = $this->userRepository->getById($command->organizerId());

        $training = Training::schedule(
            $this->trainingRepository->nextIdentity(),
            $organizer->userId(),
            $command->country(),
            $command->title(),
            $command->scheduledDate()
        );

        $this->trainingRepository->save($training);

        $this->eventDispatcher->dispatchAll($training->releaseEvents());

        return $training->trainingId();
    }
}
