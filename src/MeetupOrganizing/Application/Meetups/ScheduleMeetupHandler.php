<?php
declare(strict_types=1);

namespace MeetupOrganizing\Application\Meetups;

use MeetupOrganizing\Domain\Model\Meetup\Meetup;
use MeetupOrganizing\Domain\Model\Meetup\MeetupId;
use MeetupOrganizing\Domain\Model\Meetup\MeetupRepository;

final class ScheduleMeetupHandler
{
    private MeetupRepository $meetupRepository;

    public function __construct(MeetupRepository $meetupRepository)
    {
        $this->meetupRepository = $meetupRepository;
    }

    public function handle(ScheduleMeetup $command): MeetupId
    {
        $meetup = Meetup::schedule(
            $this->meetupRepository->nextIdentity(),
            $command->organizerId(),
            $command->countryCode(),
            $command->title(),
            $command->scheduledDate()
        );

        $this->meetupRepository->save($meetup);

        return $meetup->meetupId();
    }
}
