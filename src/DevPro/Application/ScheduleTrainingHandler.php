<?php
declare(strict_types=1);

namespace DevPro\Application;

use DevPro\Domain\Model\Training\CouldNotScheduleTraining;
use DevPro\Domain\Model\Training\Training;
use DevPro\Domain\Model\Training\TrainingId;
use DevPro\Domain\Model\Training\TrainingRepository;
use DevPro\Infrastructure\Holidays\AbstractApiClient;

final class ScheduleTrainingHandler
{
    private TrainingRepository $trainingRepository;
    private AbstractApiClient $abstractApiClient;

    public function __construct(
        TrainingRepository $trainingRepository,
        AbstractApiClient $abstractApiClient
    ) {
        $this->trainingRepository = $trainingRepository;
        $this->abstractApiClient = $abstractApiClient;
    }

    public function handle(ScheduleTraining $command): TrainingId
    {
        if ($this->abstractApiClient->getHolidays(
            $command->scheduledDate()->year(),
            $command->scheduledDate()->month(),
            $command->scheduledDate()->day(),
            $command->country()->asString()
        ) !== []) {
            throw CouldNotScheduleTraining::becauseTheDateIsANationalHolidayInThisCountry(
                $command->scheduledDate(),
                $command->country()
            );
        }

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
