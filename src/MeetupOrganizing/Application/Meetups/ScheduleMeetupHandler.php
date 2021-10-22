<?php
declare(strict_types=1);

namespace MeetupOrganizing\Application\Meetups;

use MeetupOrganizing\Domain\Model\Common\NationalHoliday;
use MeetupOrganizing\Domain\Model\Meetup\CouldNotScheduleMeetup;
use MeetupOrganizing\Domain\Model\Meetup\Meetup;
use MeetupOrganizing\Domain\Model\Meetup\MeetupId;
use MeetupOrganizing\Domain\Model\Meetup\MeetupRepository;
use MeetupOrganizing\Domain\Model\User\UserRepository;

final class ScheduleMeetupHandler
{
    private MeetupRepository $meetupRepository;
    private UserRepository $userRepository;
    private NationalHoliday $nationalHoliday;

    public function __construct(
        MeetupRepository $meetupRepository,
        UserRepository $userRepository,
        NationalHoliday $nationalHoliday
    ) {
        $this->meetupRepository = $meetupRepository;
        $this->userRepository = $userRepository;
        $this->nationalHoliday = $nationalHoliday;
    }

    public function handle(ScheduleMeetup $command): MeetupId
    {
        $organizer = $this->userRepository->getById($command->organizerId());
        if (!$organizer->isOrganizer()) {
            throw CouldNotScheduleMeetup::becauseTheUserIsNoOrganizer();
        }

        if ($this->nationalHoliday->isNationalHoliday($command->countryCode(), $command->scheduledDate()->asDate())) {
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
