<?php
declare(strict_types=1);

namespace DevPro\Domain\Model\Training;

use Assert\Assert;
use DevPro\Domain\Model\Common\EventRecordingCapabilities;
use DevPro\Domain\Model\Ticket\Ticket;
use DevPro\Domain\Model\Ticket\TicketId;
use DevPro\Domain\Model\User\UserId;

final class Training
{
    use EventRecordingCapabilities;

    /**
     * @var TrainingId
     */
    private $trainingId;

    /**
     * @var UserId
     */
    private $organizerId;

    /**
     * @var string
     */
    private $title;

    /**
     * @var ScheduledDate
     */
    private $scheduledDate;

    /**
     * @var array & UserId[]
     */
    private $attendees = [];

    /**
     * @var int
     */
    private $maximumNumberOfAttendees;

    private function __construct(
        TrainingId $trainingId,
        UserId $organizerId,
        string $title,
        ScheduledDate $scheduledDate,
        int $maximumNumberOfAttendees
    ) {
        Assert::that($title)->notEmpty('Title should not be empty');
        Assert::that($maximumNumberOfAttendees)->greaterThan(0, 'The maximum number of attendees should be greater than 0');

        $this->trainingId = $trainingId;
        $this->organizerId = $organizerId;
        $this->title = $title;
        $this->scheduledDate = $scheduledDate;
        $this->maximumNumberOfAttendees = $maximumNumberOfAttendees;
    }

    public static function schedule(
        TrainingId $trainingId,
        UserId $organizerId,
        string $title,
        ScheduledDate $scheduledDate,
        int $maximumNumberOfAttendees
    ): self {
        $training = new self(
            $trainingId,
            $organizerId,
            $title,
            $scheduledDate,
            $maximumNumberOfAttendees
        );

        $training->recordThat(new TrainingWasScheduled($trainingId, $title, $scheduledDate));

        return $training;
    }

    public function trainingId(): TrainingId
    {
        return $this->trainingId;
    }

    public function registerAttendee(UserId $userId): void
    {
        $this->attendees[] = $userId;

        $this->recordThat(new AttendeeWasRegistered($this->trainingId, $userId));

        if (count($this->attendees) >= $this->maximumNumberOfAttendees) {
            $this->recordThat(new MaximumNumberOfAttendeesWasReached($this->trainingId));
        }
    }

    public function buyTicket(TicketId $ticketId, UserId $userId): Ticket
    {
        return Ticket::buyForTraining($ticketId, $userId, $this->trainingId);
    }
}
