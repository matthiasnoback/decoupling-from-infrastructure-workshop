<?php
declare(strict_types=1);

namespace MeetupOrganizing\Application\Meetups;

use MeetupOrganizing\Domain\Model\Meetup\CouldNotScheduleMeetup;
use MeetupOrganizing\Domain\Model\Meetup\Meetup;
use MeetupOrganizing\Domain\Model\Meetup\MeetupId;
use MeetupOrganizing\Domain\Model\Meetup\MeetupRepository;
use MeetupOrganizing\Domain\Model\User\UserRepository;
use MeetupOrganizing\Infrastructure\Holidays\AbstractApiClient;

final class ScheduleMeetupHandler
{
    private MeetupRepository $meetupRepository;
    private UserRepository $userRepository;
    private AbstractApiClient $abstractApiClient;

    public function __construct(
        MeetupRepository $meetupRepository,
        UserRepository $userRepository,
        AbstractApiClient $abstractApiClient
    ) {
        $this->meetupRepository = $meetupRepository;
        $this->userRepository = $userRepository;
        $this->abstractApiClient = $abstractApiClient;
    }

    public function handle(ScheduleMeetup $command): MeetupId
    {
        $organizer = $this->userRepository->getById($command->organizerId());
        if (!$organizer->isOrganizer()) {
            throw CouldNotScheduleMeetup::becauseTheUserIsNoOrganizer();
        }

        if (count($this->abstractApiClient->getHolidays(
            $command->scheduledDate()->year(),
            $command->scheduledDate()->month(),
            $command->scheduledDate()->day(),
            $command->countryCode()->asString()
        )) > 0) {
            throw CouldNotScheduleMeetup::becauseTheDateIsANationalHolidayInThisCountry(
                $command->scheduledDate(),
                $command->countryCode()
            );
        }

        $meetup = Meetup::schedule(
            $this->meetupRepository->nextIdentity(),
            $organizer->userId(),
            $command->countryCode(),
            $command->title(),
            $command->description(),
            $command->scheduledDate()
        );

        $this->meetupRepository->save($meetup);

        return $meetup->meetupId();
    }
}
