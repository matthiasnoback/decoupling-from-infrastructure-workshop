<?php
declare(strict_types=1);

namespace DevPro\Application\ScheduleTraining;

use Common\EventDispatcher\EventDispatcher;
use DevPro\Domain\Model\Training\Training;
use DevPro\Domain\Model\Training\TrainingId;
use DevPro\Domain\Model\Training\TrainingRepository;

final class ScheduleTrainingHandler
{
    private TrainingRepository $trainingRepository;
    private EventDispatcher $eventDispatcher;

    public function __construct(TrainingRepository $trainingRepository, EventDispatcher $eventDispatcher)
    {
        $this->trainingRepository = $trainingRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function handle(ScheduleTraining $command): TrainingId
    {
        $training = Training::schedule(
            $this->trainingRepository->nextIdentity(),
            $command->organizerId(),
            $command->country(),
            $command->title(),
            $command->scheduledDate()
        );

        $this->eventDispatcher->dispatchAll($training->releaseEvents());

        return $training->trainingId();
    }
}
