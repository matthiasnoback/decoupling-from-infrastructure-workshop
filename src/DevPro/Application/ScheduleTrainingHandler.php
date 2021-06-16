<?php
declare(strict_types=1);

namespace DevPro\Application;

use DevPro\Domain\Model\Training\Training;
use DevPro\Domain\Model\Training\TrainingId;
use DevPro\Domain\Model\Training\TrainingRepository;

final class ScheduleTrainingHandler
{
    private TrainingRepository $trainingRepository;

    public function __construct(
        TrainingRepository $trainingRepository
    ) {
        $this->trainingRepository = $trainingRepository;
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
        $this->trainingRepository->save($training);

        return $training->trainingId();
    }
}
