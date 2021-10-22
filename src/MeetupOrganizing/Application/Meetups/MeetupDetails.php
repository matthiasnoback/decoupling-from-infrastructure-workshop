<?php
declare(strict_types=1);

namespace MeetupOrganizing\Application\Meetups;

use MeetupOrganizing\Domain\Model\Common\Mapping;

final class MeetupDetails
{
    use Mapping;

    private string $meetupId;
    private string $dateAndTime;
    private string $title;
    private string $description;

    /**
     * @var array<string>
     */
    private array $attendeeNames;

    /**
     * @param array<string> $attendeeNames
     */
    public function __construct(string $meetupId, string $dateAndTime, string $title, string $description, array $attendeeNames)
    {
        $this->meetupId = $meetupId;
        $this->dateAndTime = $dateAndTime;
        $this->title = $title;
        $this->description = $description;
        $this->attendeeNames = $attendeeNames;
    }

    /**
     * @param array<string,string|null> $meetupRecord
     * @param array<string,string|null> $rsvpRecords
     */
    public static function fromDatabaseRecord(array $meetupRecord, array $rsvpRecords): self
    {
        return new self(
            self::getString($meetupRecord, 'meetupId'),
            self::getString($meetupRecord, 'scheduledDate'),
            self::getString($meetupRecord, 'title'),
            self::getString($meetupRecord, 'description'),
            array_map(fn (array $rsvpRecord) => self::getString($rsvpRecord, 'attendeeName'), $rsvpRecords)
        );
    }

    public function dateAndTime(): string
    {
        return (new \DateTimeImmutable($this->dateAndTime))->format('r');
    }

    public function title(): string
    {
        return $this->title;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function meetupId(): string
    {
        return $this->meetupId;
    }

    /**
     * @return array<string>
     */
    public function attendeeNames(): array
    {
        return $this->attendeeNames;
    }
}
