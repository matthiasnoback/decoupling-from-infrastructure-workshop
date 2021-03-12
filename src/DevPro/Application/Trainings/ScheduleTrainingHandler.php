<?php
declare(strict_types=1);

namespace DevPro\Application\Trainings;

use Common\EventDispatcher\EventDispatcher;
use DevPro\Domain\Model\Training\CouldNotScheduleTraining;
use DevPro\Domain\Model\Training\Training;
use DevPro\Domain\Model\Training\TrainingId;
use DevPro\Domain\Model\Training\TrainingRepository;
use DevPro\Domain\Model\User\UserRepository;
use DevPro\Domain\Service\NationalHolidays;

final class ScheduleTrainingHandler
{
    private TrainingRepository $trainingRepository;
    private UserRepository $userRepository;
    private EventDispatcher $eventDispatcher;
    private NationalHolidays $nationalHolidays;

    public function __construct(
        TrainingRepository $trainingRepository,
        UserRepository $userRepository,
        NationalHolidays $nationalHolidays,
        EventDispatcher $eventDispatcher
    ) {
        $this->trainingRepository = $trainingRepository;
        $this->userRepository = $userRepository;
        $this->nationalHolidays = $nationalHolidays;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function handle(ScheduleTraining $command): TrainingId
    {
        $organizer = $this->userRepository->getById($command->organizerId());

        if ($this->nationalHolidays->isNationalHoliday(
            $command->country(),
            $command->scheduledDate()
        )) {
            throw CouldNotScheduleTraining::becauseTheDateOfTheTrainingIsANationalHoliday();
        }

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
