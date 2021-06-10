<?php
declare(strict_types=1);

namespace DevPro\Application;

use Common\EventDispatcher\EventDispatcher;
use DevPro\Application\Users\CreateOrganizer;
use DevPro\Application\Users\CreateOrganizerHandler;
use DevPro\Application\Users\CreateUser;
use DevPro\Application\Users\CreateUserHandler;
use DevPro\Domain\Model\Training\CouldNotScheduleTraining;
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
    private UpcomingTrainings $upcomingTrainings;
    private NationalHolidays $nationalHolidays;

    public function __construct(
        CreateUserHandler $createUserHandler,
        CreateOrganizerHandler $createOrganizerHandler,
        TrainingRepository $trainingRepository,
        EventDispatcher $eventDispatcher,
        UpcomingTrainings $upcomingTrainings,
        NationalHolidays $nationalHolidays
    ) {
        $this->createUserHandler = $createUserHandler;
        $this->createOrganizerHandler = $createOrganizerHandler;
        $this->trainingRepository = $trainingRepository;
        $this->eventDispatcher = $eventDispatcher;
        $this->upcomingTrainings = $upcomingTrainings;
        $this->nationalHolidays = $nationalHolidays;
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
        return $this->upcomingTrainings->findAllUpcomingTrainings();
    }

    public function scheduleTraining(ScheduleTraining $command): TrainingId
    {
        if ($this->nationalHolidays->isNationalHolidayInCountry(
            $command->scheduledDate(),
            $command->country()
        )) {
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

        $this->eventDispatcher->dispatchAll($training->releaseEvents());

        return $training->trainingId();
    }
}
