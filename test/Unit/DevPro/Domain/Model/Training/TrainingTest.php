<?php

namespace DevPro\Domain\Model\Training;

use DateTimeImmutable;
use DevPro\Domain\Model\Ticket\Ticket;
use DevPro\Domain\Model\Ticket\TicketId;
use DevPro\Domain\Model\User\UserId;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class TrainingTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_be_scheduled(): void
    {
        $trainingId = $this->someTrainingId();
        $organizerId = $this->someUserId();
        $title = $this->someTitle();
        $scheduledDate = $this->someDate();

        $training = Training::schedule($trainingId, $organizerId, $title, $scheduledDate, $this->aNumberOfAttendees());

        self::assertEquals(
            [new TrainingWasScheduled($trainingId, $title, $scheduledDate)],
            $training->releaseEvents()
        );
    }

    /**
     * @test
     */
    public function the_title_should_not_be_empty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageRegExp('/title/i');

        Training::schedule(
            $this->someTrainingId(),
            $this->someUserId(),
            $emptyTitle = '',
            $this->someDate(),
            $this->aNumberOfAttendees()
        );
    }

    /**
     * @test
     */
    public function the_maximum_number_of_attendees_should_be_greater_than_0(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageRegExp('/attendees/i');

        Training::schedule(
            $this->someTrainingId(),
            $this->someUserId(),
            $this->someDescription(),
            $this->someDate(),
            0
        );
    }

    /**
     * @test
     */
    public function it_accepts_user_registrations(): void
    {
        $training = $this->someTraining();

        $attendeeId = $this->someAttendeeId();
        $training->registerAttendee($attendeeId);

        self::assertEquals(
            [
                new AttendeeWasRegistered($training->trainingId(), $attendeeId)
            ],
            $training->releaseEvents()
        );
    }

    /**
     * @test
     */
    public function you_can_register_attendees_until_the_maximum_is_reached(): void
    {
        $training = $this->someTrainingWithAMaximumOfAttendees(2);

        $attendeeId1 = $this->someAttendeeId();
        $training->registerAttendee($attendeeId1);
        $attendeeId2 = $this->anotherAttendeeId();
        $training->registerAttendee($attendeeId2);

        self::assertEquals(
            [
                new AttendeeWasRegistered($training->trainingId(), $attendeeId1),
                new AttendeeWasRegistered($training->trainingId(), $attendeeId2),
                new MaximumNumberOfAttendeesWasReached($training->trainingId())
            ],
            $training->releaseEvents()
        );
    }

    /**
     * @test
     */
    public function you_can_buy_a_ticket_for_it(): void
    {
        $training = $this->someTraining();
        $ticketId = TicketId::fromString('119cb3c1-0657-4475-b683-2a8b94215f60');
        $userId = $this->someUserId();

        self::assertEquals(
            Ticket::buyForTraining($ticketId, $userId, $training->trainingId()),
            $training->buyTicket($ticketId, $userId)
        );
    }

    private function someTraining(): Training
    {
        $training = Training::schedule(
            $this->someTrainingId(),
            $this->someUserId(),
            $this->someDescription(),
            $this->someDate(),
            $this->aNumberOfAttendees()
        );

        $training->releaseEvents();

        return $training;
    }

    private function someTrainingWithAMaximumOfAttendees(int $maximumNumberOfAttendees): Training
    {
        $training = Training::schedule(
            $this->someTrainingId(),
            $this->someUserId(),
            $this->someDescription(),
            $this->someDate(),
            $maximumNumberOfAttendees
        );

        $training->releaseEvents();

        return $training;
    }

    private function someTrainingId(): TrainingId
    {
        return TrainingId::fromString('eaa631d0-3760-43f5-a8cf-f239aadfe4aa');
    }

    private function someDate(): ScheduledDate
    {
        return ScheduledDate::fromDateTime(new DateTimeImmutable('now'));
    }

    private function someTitle(): string
    {
        return 'Some title';
    }

    private function someUserId()
    {
        return UserId::fromString('bb235de9-c15d-4bd8-9bc3-d31e4cc0e96f');
    }

    private function someAttendeeId(): UserId
    {
        return UserId::fromString('e2ea76c5-e1bc-4e85-b4bf-79d18952592d');
    }

    private function anotherAttendeeId(): UserId
    {
        return UserId::fromString('3a0f599f-f384-45b8-8fd9-54864b850c59');
    }

    private function someDescription(): string
    {
        return 'Some description';
    }

    private function aNumberOfAttendees(): int
    {
        return 10;
    }
}
